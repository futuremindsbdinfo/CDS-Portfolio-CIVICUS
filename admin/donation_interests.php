<?php
// admin/donation_interests.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Handle Status Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    $status = clean_input($_POST['status']);
    
    if (in_array($status, ['pending', 'verified', 'rejected']) && $db) {
        $stmt = $db->prepare("UPDATE donation_interests SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $id])) {
            $_SESSION['flash_message'] = "Donation status updated to " . ucfirst($status) . ".";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to update donation status.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: donation_interests.php");
    exit;
}

// Handle Delete (optional but good to have)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("DELETE FROM donation_interests WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['flash_message'] = "Donation record deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete donation record.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: donation_interests.php");
    exit;
}

// Fetch donations
$donations = [];
$total_pending = 0;
$total_verified_amount = 0;

if ($db) {
    // Stats
    $total_pending = $db->query("SELECT COUNT(*) FROM donation_interests WHERE status = 'pending'")->fetchColumn() ?: 0;
    $total_verified_amount = $db->query("SELECT SUM(donation_amount) FROM donation_interests WHERE status = 'verified'")->fetchColumn() ?: 0;

    // Filter
    $filter_status = isset($_GET['status']) ? clean_input($_GET['status']) : 'all';
    
    // Pagination setup
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $where_clause = "";
    $params = [];
    if (in_array($filter_status, ['pending', 'verified', 'rejected'])) {
        $where_clause = "WHERE status = :status";
        $params[':status'] = $filter_status;
    }

    $total_stmt = $db->prepare("SELECT COUNT(*) FROM donation_interests $where_clause");
    foreach($params as $key => $val) {
        $total_stmt->bindValue($key, $val);
    }
    $total_stmt->execute();
    $total_donations = $total_stmt->fetchColumn();
    $total_pages = ceil($total_donations / $limit);

    $stmt = $db->prepare("SELECT * FROM donation_interests $where_clause ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    foreach($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $donations = $stmt->fetchAll();
}
?>

<div>
    <div class="mb-8">
        <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Donation Interests</h1>
        <p class="mt-1 text-sm text-slate-500">জমা হওয়া দানের আগ্রহপত্রসমূহ</p>
    </div>

    <!-- Stat strip -->
    <div class="mb-5 grid gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="text-xs font-medium text-amber-700">Pending</div>
            <div class="mt-1 font-serif-bn text-2xl font-bold text-amber-900"><?php echo $total_pending; ?></div>
        </div>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
            <div class="text-xs font-medium text-emerald-700">Verified Total</div>
            <div class="mt-1 font-serif-bn text-2xl font-bold text-emerald-900">৳ <?php echo number_format($total_verified_amount); ?></div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="text-xs font-medium text-slate-500">Total Entries (Filtered)</div>
            <div class="mt-1 font-serif-bn text-2xl font-bold text-slate-900"><?php echo $total_donations ?? 0; ?></div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6 flex gap-1 rounded-lg bg-slate-100 p-1 w-full overflow-x-auto">
        <a href="?status=all" class="flex-1 rounded-md px-3 py-1.5 text-center text-sm font-semibold transition <?php echo (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'; ?>">সব</a>
        <a href="?status=pending" class="flex-1 rounded-md px-3 py-1.5 text-center text-sm font-semibold transition <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'; ?>">Pending</a>
        <a href="?status=verified" class="flex-1 rounded-md px-3 py-1.5 text-center text-sm font-semibold transition <?php echo (isset($_GET['status']) && $_GET['status'] === 'verified') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'; ?>">Verified</a>
        <a href="?status=rejected" class="flex-1 rounded-md px-3 py-1.5 text-center text-sm font-semibold transition <?php echo (isset($_GET['status']) && $_GET['status'] === 'rejected') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900'; ?>">Rejected</a>
    </div>

    <!-- Donations List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">দাতার নাম ও যোগাযোগ</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">পরিমাণ</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">মেথড ও ট্রানজেকশন</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">তারিখ</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">স্ট্যাটাস ও Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($donations)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><path d="M12 21s-7-4.5-7-10a4 4 0 017-2.7A4 4 0 0119 11c0 5.5-7 10-7 10z" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো ডোনেশন রেকর্ড পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($donations as $d): ?>
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3 align-top">
                                <div class="font-medium text-slate-900"><?php echo e($d['donor_name']); ?></div>
                                <div class="mt-0.5 text-[11px] text-slate-500 font-mono"><?php echo e($d['donor_phone']); ?></div>
                            </td>
                            <td class="px-4 py-3 align-top font-serif-bn font-bold text-primary">
                                ৳ <?php echo number_format($d['donation_amount']); ?>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <?php
                                    $m = $d['payment_method'];
                                    $methodColor = "bg-blue-100 text-blue-700";
                                    if ($m === 'bKash') $methodColor = "bg-pink-100 text-pink-700";
                                    if ($m === 'Nagad') $methodColor = "bg-orange-100 text-orange-700";
                                    if ($m === 'Rocket') $methodColor = "bg-violet-100 text-violet-700";
                                ?>
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-xs font-semibold <?php echo $methodColor; ?>">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    <?php echo e($d['payment_method']); ?>
                                </span>
                                <?php if($d['transaction_id']): ?>
                                    <div class="mt-1.5 text-xs font-mono text-slate-700"><?php echo e($d['transaction_id']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 align-top text-slate-600 whitespace-nowrap">
                                <?php echo date('d M Y', strtotime($d['created_at'])); ?><br>
                                <span class="text-xs text-slate-400"><?php echo date('h:i A', strtotime($d['created_at'])); ?></span>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="mb-2">
                                    <?php if($d['status'] === 'verified'): ?>
                                        <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-500/20">Verified</span>
                                    <?php elseif($d['status'] === 'pending'): ?>
                                        <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-500/20">Pending</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-500/20">Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="donation_interests.php" method="POST" class="inline-block">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded border border-slate-200 px-1 py-1 bg-slate-50 focus:outline-none">
                                            <option value="pending" <?php echo $d['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="verified" <?php echo $d['status'] === 'verified' ? 'selected' : ''; ?>>Verified</option>
                                            <option value="rejected" <?php echo $d['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </form>
                                    
                                    <button type="button" class="grid h-6 w-6 place-items-center rounded border border-slate-200 bg-white text-blue-600 hover:bg-blue-50 transition" title="View Details" onclick='openDonationModal(<?php echo json_encode([
                                        "name" => $d["donor_name"],
                                        "email" => $d["donor_email"] ?? "N/A",
                                        "phone" => $d["donor_phone"],
                                        "amount" => $d["donation_amount"],
                                        "method" => $d["payment_method"],
                                        "transaction" => $d["transaction_id"] ?? "N/A",
                                        "ip" => $d["ip_address"] ?? "N/A",
                                        "date" => date("d M Y, h:i A", strtotime($d["created_at"])),
                                        "status" => $d["status"]
                                    ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-3.5 w-3.5"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    
                                    <form action="donation_interests.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="inline-block">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                                        <button type="submit" class="grid h-6 w-6 place-items-center rounded border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-3.5 w-3.5"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(isset($total_pages) && $total_pages > 1): ?>
        <div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-700">
                        Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to <span class="font-medium"><?php echo min($offset + $limit, $total_donations); ?></span> of <span class="font-medium"><?php echo $total_donations; ?></span> results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <?php
                            $q_status = isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '';
                        ?>
                        <?php if($page > 1): ?>
                            <a href="?page=<?php echo $page-1 . $q_status; ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?page=<?php echo $i . $q_status; ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?php echo $i === $page ? 'z-10 bg-primary text-primary-foreground focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary' : 'text-slate-900 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0'; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if($page < $total_pages): ?>
                            <a href="?page=<?php echo $page+1 . $q_status; ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Donation Details Modal -->
<div id="donationModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-serif-bn font-semibold leading-6 text-slate-900" id="modal-title">ডোনেশন বিস্তারিত</h3>
                        <button type="button" onclick="closeDonationModal()" class="rounded-md bg-white text-slate-400 hover:text-slate-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                        </button>
                    </div>
                    <div class="mt-4 grid gap-y-4 text-sm text-slate-700">
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Name</span>
                            <span class="col-span-2 font-semibold text-slate-900" id="modal-name"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Email</span>
                            <span class="col-span-2" id="modal-email"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Phone</span>
                            <span class="col-span-2 font-mono" id="modal-phone"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Amount</span>
                            <span class="col-span-2 font-serif-bn font-bold text-primary text-base" id="modal-amount"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Method</span>
                            <span class="col-span-2" id="modal-method"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Transaction ID</span>
                            <span class="col-span-2 font-mono text-xs bg-slate-100 px-1 py-0.5 rounded" id="modal-transaction"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">Date</span>
                            <span class="col-span-2" id="modal-date"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-slate-50 pb-2">
                            <span class="font-medium text-slate-500">IP Address</span>
                            <span class="col-span-2 font-mono text-xs text-slate-400" id="modal-ip"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pb-2">
                            <span class="font-medium text-slate-500">Status</span>
                            <span class="col-span-2 uppercase text-xs font-bold" id="modal-status"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeDonationModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('donationModal');
    
    function openDonationModal(data) {
        document.getElementById('modal-name').textContent = data.name;
        document.getElementById('modal-email').textContent = data.email || 'N/A';
        document.getElementById('modal-phone').textContent = data.phone;
        document.getElementById('modal-amount').textContent = '৳ ' + Number(data.amount).toLocaleString();
        document.getElementById('modal-method').textContent = data.method;
        document.getElementById('modal-transaction').textContent = data.transaction || 'N/A';
        document.getElementById('modal-date').textContent = data.date;
        document.getElementById('modal-ip').textContent = data.ip || 'N/A';
        
        const statusEl = document.getElementById('modal-status');
        statusEl.textContent = data.status;
        if(data.status === 'verified') statusEl.className = 'col-span-2 uppercase text-xs font-bold text-emerald-600';
        else if(data.status === 'pending') statusEl.className = 'col-span-2 uppercase text-xs font-bold text-amber-600';
        else statusEl.className = 'col-span-2 uppercase text-xs font-bold text-rose-600';

        modal.classList.remove('hidden');
    }

    function closeDonationModal() {
        modal.classList.add('hidden');
    }

    // Close on click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal.firstElementChild) {
            closeDonationModal();
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
