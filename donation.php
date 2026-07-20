<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="bg-green-500 py-12 md:py-16 text-white text-center">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">
            <span data-lang="bn">ডোনেট করুন</span>
            <span data-lang="en" class="hidden">Donate</span>
        </h1>
        <p class="text-lg text-green-100 max-w-2xl mx-auto">
            <span data-lang="bn">আপনার সামান্য অনুদান সমাজের অবহেলিত মানুষের মুখে হাসি ফোটাতে পারে।</span>
            <span data-lang="en" class="hidden">Your small contribution can bring a smile to the faces of marginalized people in society.</span>
        </p>
    </div>
</div>

<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Donation Information (Bank/bKash Details) -->
            <div class="lg:w-1/2">
                <h2 class="text-2xl font-serif font-bold text-cds-blue mb-6">
                    <span data-lang="bn">অনুদান পাঠানোর মাধ্যম</span>
                    <span data-lang="en" class="hidden">Donation Methods</span>
                </h2>
                
                <div class="space-y-6">
                    <!-- Bank Details -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center mb-4 text-cds-blue">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <h3 class="text-xl font-bold" data-lang="bn">ব্যাংক অ্যাকাউন্ট</h3>
                            <h3 class="text-xl font-bold hidden" data-lang="en">Bank Account</h3>
                        </div>
                        <ul class="text-gray-700 space-y-2">
                            <li><strong>Bank Name:</strong> Sonali Bank PLC</li>
                            <li><strong>Account Name:</strong> Citizen Development Society</li>
                            <li><strong>Account Number:</strong> 0000 1234 5678</li>
                            <li><strong>Branch:</strong> Motijheel Branch, Dhaka</li>
                            <li><strong>Routing No:</strong> 123456789</li>
                        </ul>
                    </div>

                    <!-- Mobile Banking Details -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center mb-4 text-[#e2136e]"> <!-- bKash pink color -->
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <h3 class="text-xl font-bold" data-lang="bn">মোবাইল ব্যাংকিং</h3>
                            <h3 class="text-xl font-bold hidden" data-lang="en">Mobile Banking</h3>
                        </div>
                        <ul class="text-gray-700 space-y-2">
                            <li><strong>bKash (Merchant):</strong> 01700-000000</li>
                            <li><strong>Nagad (Merchant):</strong> 01700-000000</li>
                            <li><strong>Rocket (Merchant):</strong> 01700-000000-0</li>
                        </ul>
                        <p class="text-sm text-gray-500 mt-4">
                            <span data-lang="bn">* পেমেন্ট করার পর ট্রানজ্যাকশন আইডি (TrxID) সংরক্ষণ করুন এবং পাশের ফর্মে সাবমিট করুন।</span>
                            <span data-lang="en" class="hidden">* After payment, save the Transaction ID (TrxID) and submit it in the form.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Donation Interest Form -->
            <div class="lg:w-1/2">
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-md border-t-4 border-cds-green">
                    <h2 class="text-2xl font-serif font-bold text-cds-blue mb-2">
                        <span data-lang="bn">ডোনেশন ইন্টারেস্ট ফর্ম</span>
                        <span data-lang="en" class="hidden">Donation Interest Form</span>
                    </h2>
                    <p class="text-gray-600 mb-6 text-sm">
                        <span data-lang="bn">অনুদান পাঠানোর পর তথ্য নিশ্চিত করার জন্য এই ফর্মটি পূরণ করুন। আমাদের প্রতিনিধি আপনার সাথে যোগাযোগ করবেন।</span>
                        <span data-lang="en" class="hidden">Fill out this form to confirm your information after sending the donation. Our representative will contact you.</span>
                    </p>

                    <form action="#" method="POST" class="space-y-4">
                        <!-- CSRF Token placeholder -->
                        <input type="hidden" name="csrf_token" value="dummy_token_for_phase_4">
                        
                        <div>
                            <label for="donor_name" class="block text-sm font-semibold text-gray-700 mb-1">
                                <span data-lang="bn">আপনার নাম *</span>
                                <span data-lang="en" class="hidden">Your Name *</span>
                            </label>
                            <input type="text" id="donor_name" name="donor_name" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent">
                        </div>

                        <div>
                            <label for="donor_phone" class="block text-sm font-semibold text-gray-700 mb-1">
                                <span data-lang="bn">ফোন নম্বর *</span>
                                <span data-lang="en" class="hidden">Phone Number *</span>
                            </label>
                            <input type="tel" id="donor_phone" name="donor_phone" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent">
                        </div>

                        <div>
                            <label for="donor_email" class="block text-sm font-semibold text-gray-700 mb-1">
                                <span data-lang="bn">ইমেইল (ঐচ্ছিক)</span>
                                <span data-lang="en" class="hidden">Email (Optional)</span>
                            </label>
                            <input type="email" id="donor_email" name="donor_email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="donation_amount" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">পরিমাণ (BDT) *</span>
                                    <span data-lang="en" class="hidden">Amount (BDT) *</span>
                                </label>
                                <input type="number" id="donation_amount" name="donation_amount" min="10" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent">
                            </div>
                            <div>
                                <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span data-lang="bn">মাধ্যম *</span>
                                    <span data-lang="en" class="hidden">Method *</span>
                                </label>
                                <select id="payment_method" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent bg-white">
                                    <option value="" disabled selected data-lang="bn">নির্বাচন করুন</option>
                                    <option value="" disabled selected class="hidden" data-lang="en">Select</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="cash">Cash (Office)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="transaction_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                <span data-lang="bn">ট্রানজ্যাকশন আইডি (TrxID)</span>
                                <span data-lang="en" class="hidden">Transaction ID (TrxID)</span>
                            </label>
                            <input type="text" id="transaction_id" name="transaction_id" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cds-green focus:border-transparent">
                        </div>

                        <button type="submit" class="w-full bg-cds-green text-white font-bold py-3 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-colors mt-4">
                            <span data-lang="bn">সাবমিট করুন</span>
                            <span data-lang="en" class="hidden">Submit</span>
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
