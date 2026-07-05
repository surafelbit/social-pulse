<?  
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch notifications and eager-load the sender's details
        $notifications = $user->notifications()->with('sender')->latest()->get();
        
        // Mark all as read
        $user->notifications()->update(['is_read' => true]);

        return Inertia::render('Notifications', ['notifications' => $notifications]);
    }
}