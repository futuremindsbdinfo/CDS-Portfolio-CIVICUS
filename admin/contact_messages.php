<?php
// admin/contact_messages.php
require_once __DIR__ . '/includes/header.php';

$db = Database::getConnection();

// Handle Mark as Read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_read') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['flash_message'] = "Message marked as read.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to mark message as read.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: contact_messages.php");
    exit;
}

// Handle Delete (optional but good to have)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) { die("CSRF token validation failed."); }
    $id = (int)$_POST['id'];
    if ($db) {
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['flash_message'] = "Message deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete message.";
            $_SESSION['flash_type'] = "error";
        }
    }
    header("Location: contact_messages.php");
    exit;
}

// Fetch messages
$messages = [];
$current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
if ($db) {
    // Pagination setup
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $where_clause = "";
    if ($current_filter === 'unread') {
        $where_clause = " WHERE is_read = 0";
    } elseif ($current_filter === 'read') {
        $where_clause = " WHERE is_read = 1";
    }

    $total_stmt = $db->query("SELECT COUNT(*) FROM contact_messages" . $where_clause);
    $total_messages = $total_stmt->fetchColumn();
    $total_pages = ceil($total_messages / $limit);

    $stmt = $db->prepare("SELECT * FROM contact_messages" . $where_clause . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll();
}
?>

<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="font-serif-bn text-2xl font-bold text-slate-900">Contact Messages</h1>
            <p class="mt-1 text-sm text-slate-500">ওয়েবসাইটের ফর্ম থেকে আসা সকল বার্তা</p>
        </div>
        <div class="flex bg-slate-100 p-1 rounded-lg w-max">
            <a href="?filter=all" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php echo $current_filter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">All</a>
            <a href="?filter=unread" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php echo $current_filter === 'unread' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">Unread</a>
            <a href="?filter=read" class="px-4 py-2 rounded-md text-sm font-medium transition-colors <?php echo $current_filter === 'read' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50'; ?>">Read</a>
        </div>
    </div>

    <!-- Messages List -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs text-slate-500">
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Sender</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Subject & Message</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if(empty($messages)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">
                            <div class="grid place-items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-7 w-7"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M3 7l9 6 9-6" /></svg>
                                </div>
                                <div class="font-serif-bn text-sm font-semibold text-slate-700">কোনো মেসেজ পাওয়া যায়নি</div>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($messages as $msg): ?>
                        <tr class="hover:bg-slate-50/50 <?php echo $msg['is_read'] ? 'opacity-70' : ''; ?>">
                            <td class="px-4 py-3 align-top">
                                <div class="font-medium text-slate-900"><?php echo e($msg['name']); ?></div>
                                <div class="mt-0.5 text-[11px] text-slate-500 font-mono"><?php echo e($msg['phone']); ?></div>
                                <div class="mt-0.5 text-[11px] text-slate-500"><?php echo e($msg['email']); ?></div>
                            </td>
                            <td class="px-4 py-3 align-top max-w-md">
                                <div class="font-medium text-slate-900 mb-1"><?php echo e($msg['subject']); ?></div>
                                <p class="text-xs text-slate-600 line-clamp-3"><?php echo nl2br(e($msg['message'])); ?></p>
                                <div class="mt-2 text-[10px] text-slate-400 font-mono">IP: <?php echo e($msg['ip_address']); ?></div>
                            </td>
                            <td class="px-4 py-3 align-top text-slate-600 whitespace-nowrap">
                                <?php echo date('d M Y', strtotime($msg['created_at'])); ?><br>
                                <span class="text-xs text-slate-400"><?php echo date('h:i A', strtotime($msg['created_at'])); ?></span>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <?php if($msg['is_read']): ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">Read</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-500/20">Unread</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 align-top flex gap-2 h-full">
                                <button type="button" onclick="openMessageModal(this)" data-message="<?php echo htmlspecialchars(json_encode($msg), ENT_QUOTES, 'UTF-8'); ?>" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-blue-600 hover:bg-blue-50 transition" title="View Details">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <?php if(!$msg['is_read']): ?>
                                    <form action="contact_messages.php" method="POST" class="inline-block">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="action" value="mark_read">
                                        <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                        <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-emerald-600 hover:bg-emerald-50 transition" title="Mark as Read">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M5 12l4 4 10-10" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="contact_messages.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');" class="inline-block">
                                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4"><path d="M4 7h16M9 7V5h6v2M6 7l1 13h10l1-13" stroke-linejoin="round" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($total_pages > 1): ?>
        <div class="flex items-center justify-between border-t border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-700">
                        Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to <span class="font-medium"><?php echo min($offset + $limit, $total_messages); ?></span> of <span class="font-medium"><?php echo $total_messages; ?></span> results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <?php if($page > 1): ?>
                            <a href="?filter=<?php echo urlencode($current_filter); ?>&page=<?php echo $page-1; ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?filter=<?php echo urlencode($current_filter); ?>&page=<?php echo $i; ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?php echo $i === $page ? 'z-10 bg-primary text-primary-foreground focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary' : 'text-slate-900 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0'; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if($page < $total_pages): ?>
                            <a href="?filter=<?php echo urlencode($current_filter); ?>&page=<?php echo $page+1; ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0">
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
    <!-- Message Details Modal -->
    <div id="messageModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 font-serif-bn">Message Details</h3>
                <button type="button" onclick="closeMessageModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Sender</span>
                        <div id="modalSenderName" class="font-medium text-slate-900 mt-1"></div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Date</span>
                        <div id="modalDate" class="text-slate-900 mt-1"></div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Email</span>
                        <div id="modalEmail" class="text-slate-900 mt-1"></div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Phone</span>
                        <div id="modalPhone" class="text-slate-900 mt-1 font-mono"></div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">IP Address</span>
                        <div id="modalIp" class="text-slate-900 mt-1 font-mono text-sm"></div>
                    </div>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Subject</span>
                    <div id="modalSubject" class="font-medium text-slate-900 mt-1 text-lg mb-4 border-b border-slate-100 pb-2"></div>
                </div>
                <div>
                    <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Message</span>
                    <div id="modalMessageContent" class="text-slate-700 mt-2 whitespace-pre-wrap rounded-lg bg-slate-50 p-4 border border-slate-100"></div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                <button type="button" onclick="closeMessageModal()" class="px-4 py-2 bg-white border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Close</button>
            </div>
        </div>
    </div>

    <script>
    function openMessageModal(button) {
        const msg = JSON.parse(button.getAttribute('data-message'));
        document.getElementById('modalSenderName').textContent = msg.name || 'N/A';
        document.getElementById('modalEmail').textContent = msg.email || 'N/A';
        document.getElementById('modalPhone').textContent = msg.phone || 'N/A';
        document.getElementById('modalIp').textContent = msg.ip_address || 'N/A';
        document.getElementById('modalSubject').textContent = msg.subject || 'No Subject';
        document.getElementById('modalMessageContent').textContent = msg.message || 'No Message';
        
        const dateObj = new Date(msg.created_at);
        document.getElementById('modalDate').textContent = dateObj.toLocaleString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true });

        const modal = document.getElementById('messageModal');
        modal.classList.remove('hidden');
    }

    function closeMessageModal() {
        const modal = document.getElementById('messageModal');
        modal.classList.add('hidden');
    }

    // Close on click outside
    document.getElementById('messageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMessageModal();
        }
    });
    </script>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
