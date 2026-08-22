<?php
require_once __DIR__ . '/includes/auth.php';
init_secure_session();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/sanitize.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mailer.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf_token)) {
        $error_message = '<span data-lang="bn">নিরাপত্তা টোকেন সঠিক নয়। দয়া করে পৃষ্ঠাটি রিফ্রেশ দিয়ে আবার চেষ্টা করুন।</span><span data-lang="en" class="hidden">CSRF security validation failed. Please refresh and try again.</span>';
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $pdo = Database::getConnection();

        // Rate limit: max 5 messages per hour from same IP
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM contact_messages WHERE ip_address = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $stmt_check->execute([$ip_address]);
        if ($stmt_check->fetchColumn() >= 5) {
            $error_message = '<span data-lang="bn">আপনি অল্প সময়ে অনেকগুলো বার্তা পাঠিয়েছেন। দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।</span><span data-lang="en" class="hidden">You have submitted too many messages. Please try again later.</span>';
        } else {
            $name = clean_input($_POST['name'] ?? '');
            $email = clean_input($_POST['email'] ?? '');
            $phone = clean_input($_POST['phone'] ?? '');
            $subject = clean_input($_POST['subject'] ?? '');
            $message = clean_input($_POST['message'] ?? '');

            if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
                $error_message = '<span data-lang="bn">অনুগ্রহ করে সব আবশ্যক ঘর পূরণ করুন।</span><span data-lang="en" class="hidden">Please fill in all required fields.</span>';
            } elseif (!validate_email($email)) {
                $error_message = '<span data-lang="bn">সঠিক ইমেইল ঠিকানা প্রদান করুন।</span><span data-lang="en" class="hidden">Please provide a valid email address.</span>';
            } else {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $phone, $subject, $message, $ip_address])) {
                    $success_message = '<span data-lang="bn">ধন্যবাদ! আপনার বার্তাটি সফলভাবে জমা হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।</span><span data-lang="en" class="hidden">Thank you! Your message has been sent successfully. We will get in touch with you shortly.</span>';
                    
                    // Send notification to Admin
                    $admin_email = get_setting('site_email', 'citizendevelopmentsociety@gmail.com');
                    if (!empty($admin_email)) {
                        $mail_sub = "ওয়েবসাইটে নতুন বার্তা: " . $subject;
                        $mail_body = "<div style='font-family:sans-serif; padding:20px; line-height:1.6; color:#333;'>
                            <h3 style='color:#0e1b64;'>সিডিএস ওয়েবসাইটে নতুন যোগাযোগের বার্তা</h3>
                            <p><strong>প্রেরক:</strong> {$name}</p>
                            <p><strong>ইমেইল:</strong> {$email}</p>
                            <p><strong>ফোন:</strong> {$phone}</p>
                            <p><strong>বিষয়:</strong> {$subject}</p>
                            <div style='background:#f1f5f9; padding:15px; border-radius:8px; margin-top:10px;'>
                                <strong>বার্তা:</strong><br>" . nl2br(htmlspecialchars($message)) . "
                            </div>
                        </div>";
                        send_cds_email($admin_email, $mail_sub, $mail_body, 'CDS Admin');
                    }
                } else {
                    $error_message = '<span data-lang="bn">বার্তা পাঠাতে সমস্যা হয়েছে। দয়া করে কিছুক্ষণ পর আবার চেষ্টা করুন।</span><span data-lang="en" class="hidden">An error occurred while sending your message. Please try again.</span>';
                }
            }
        }
    }
}
include 'includes/header.php';
?>

<!-- Page Header -->
<div class="bg-gray-500 py-12 md:py-16 text-white text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-cds-blue opacity-90"></div>
    <div class="container mx-auto px-4 relative z-10">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">যোগাযোগ করুন</span>
            <span data-lang="en" class="hidden">Contact Us</span>
        </h1>
        <p class="text-lg text-gray-200 max-w-2xl mx-auto">
            <span data-lang="bn">যেকোনো প্রশ্ন, মতামত বা পরামর্শের জন্য আমাদের সাথে যোগাযোগ করুন।</span>
            <span data-lang="en" class="hidden">Get in touch with us for any inquiries, feedback, or suggestions.</span>
        </p>
    </div>
</div>

<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Contact Information -->
            <div class="lg:w-1/3">
                <h2 class="text-2xl font-serif font-bold text-cds-blue mb-6">
                    <span data-lang="bn">আমাদের ঠিকানা</span>
                    <span data-lang="en" class="hidden">Our Address</span>
                </h2>
                
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-start mb-4 text-cds-green">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <h3 class="font-bold text-gray-800" data-lang="bn">অফিস ঠিকানা</h3>
                                <h3 class="font-bold text-gray-800 hidden" data-lang="en">Office Address</h3>
                                <p class="text-gray-600 mt-1">
                                    <span data-lang="bn"><?php echo nl2br(htmlspecialchars(get_setting('site_address', 'কেন্দ্রীয় কার্যালয়: ২৮/১, কাকরাইল, ঢাকা-১০০০, বাংলাদেশ'))); ?></span>
                                    <span data-lang="en" class="hidden"><?php echo nl2br(htmlspecialchars(get_setting('site_address_en', 'Head Office: 28/1, Kakrail, Dhaka-1000, Bangladesh'))); ?></span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-4 text-cds-green">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <div>
                                <h3 class="font-bold text-gray-800" data-lang="bn">ইমেইল</h3>
                                <h3 class="font-bold text-gray-800 hidden" data-lang="en">Email</h3>
                                <p class="text-gray-600 mt-1"><?php echo htmlspecialchars(get_setting('site_email', 'citizendevelopmentsociety@gmail.com')); ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-4 text-cds-green">
                            <svg class="w-6 h-6 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <div>
                                <h3 class="font-bold text-gray-800" data-lang="bn">ফোন</h3>
                                <h3 class="font-bold text-gray-800 hidden" data-lang="en">Phone</h3>
                                <p class="text-gray-600 mt-1"><?php echo htmlspecialchars(get_setting('site_phone', '+8801886004317')); ?></p>
                            </div>
                        </div>

                        <div class="flex items-start text-[#1877F2]">
                            <svg class="w-6 h-6 mr-3 mt-1 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <div>
                                <h3 class="font-bold text-gray-800" data-lang="bn">ফেসবুক পেজ</h3>
                                <h3 class="font-bold text-gray-800 hidden" data-lang="en">Facebook Page</h3>
                                <a href="<?php echo htmlspecialchars(get_setting('social_facebook', 'https://www.facebook.com/citizendevelopmentsociety')); ?>" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline mt-1 text-sm font-semibold block">
                                    fb.com/citizendevelopmentsociety ↗
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (get_setting('google_map_embed')): ?>
                    <div class="mt-6 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                        <?php echo get_setting('google_map_embed'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:w-2/3">
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md border-t-4 border-cds-blue">
                    <h2 class="text-2xl font-serif font-bold text-gray-800 mb-6">
                        <span data-lang="bn">বার্তা পাঠান</span>
                        <span data-lang="en" class="hidden">Send a Message</span>
                    </h2>

                    <?php if ($success_message): ?>
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-400 font-medium">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded border border-red-400 font-medium">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">আপনার নাম *</span>
                                    <span data-lang="en" class="hidden">Your Name *</span>
                                </label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-blue focus:border-transparent">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">ইমেইল *</span>
                                    <span data-lang="en" class="hidden">Email *</span>
                                </label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-blue focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">ফোন নম্বর *</span>
                                    <span data-lang="en" class="hidden">Phone Number *</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-blue focus:border-transparent">
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">বিষয় *</span>
                                    <span data-lang="en" class="hidden">Subject *</span>
                                </label>
                                <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-blue focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-1">
                                <span data-lang="bn">আপনার বার্তা *</span>
                                <span data-lang="en" class="hidden">Your Message *</span>
                            </label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-blue focus:border-transparent"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-cds-blue text-white font-bold py-3 px-4 rounded hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-colors mt-4">
                            <span data-lang="bn">বার্তা পাঠান</span>
                            <span data-lang="en" class="hidden">Send Message</span>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
