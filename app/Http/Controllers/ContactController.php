<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactMessage;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function show()
    {
        return view('contact');
    }

    /**
     * Handle form submission
     */
    public function submit(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نصاً',
            'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.max' => 'البريد الإلكتروني يجب أن لا يتجاوز 255 حرف',
            'subject.required' => 'الموضوع مطلوب',
            'subject.string' => 'الموضوع يجب أن يكون نصاً',
            'subject.max' => 'الموضوع يجب أن لا يتجاوز 255 حرف',
            'message.required' => 'الرسالة مطلوبة',
            'message.string' => 'الرسالة يجب أن تكون نصاً',
            'message.max' => 'الرسالة يجب أن لا تتجاوز 5000 حرف',
        ]);

        try {
            // Store the message in the database
            $contactMessage = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'unread',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send email notification
            $this->sendEmailNotification($contactMessage);

            // Log the successful submission
            Log::info('Contact form submitted successfully', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'شكراً لك على التواصل معنا! سنقوم بالرد عليك قريباً.');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
                'ip_address' => $request->ip(),
            ]);

            return back()->with('error', 'عذراً، حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(ContactMessage $contactMessage)
    {
        try {
            // Get admin email from config or use default
            $adminEmail = config('mail.admin_email', 'admin@gazacoupon.com');
            
            // Prepare email data
            $emailData = [
                'name' => $contactMessage->name,
                'email' => $contactMessage->email,
                'subject' => $contactMessage->subject,
                'message' => $contactMessage->message,
                'ip_address' => $contactMessage->ip_address,
            ];

            // Send email
            Mail::to($adminEmail)->send(new ContactFormMail($emailData));

            // Log successful email
            Log::info('Contact form email sent successfully', [
                'to' => $adminEmail,
                'contact_id' => $contactMessage->id,
            ]);

        } catch (\Exception $e) {
            // Log email error but don't fail the form submission
            Log::error('Failed to send contact form email', [
                'error' => $e->getMessage(),
                'contact_id' => $contactMessage->id,
            ]);
        }
    }

    /**
     * Admin: View all contact messages
     */
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        
        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * Admin: View a specific message
     */
    public function showMessage($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read if it's unread
        if (!$message->isRead()) {
            $message->markAsRead();
        }
        
        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Admin: Mark message as replied
     */
    public function markAsReplied($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsReplied();
        
        return back()->with('success', 'تم تحديث حالة الرسالة بنجاح.');
    }

    /**
     * Admin: Update message status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied,archived',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $message = ContactMessage::findOrFail($id);
        $message->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'تم تحديث حالة الرسالة بنجاح.');
    }

    /**
     * Admin: Delete message
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return back()->with('success', 'تم حذف الرسالة بنجاح.');
    }

    /**
     * Get contact statistics for admin dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'recent_messages' => ContactMessage::recent(7)->count(),
            'messages_by_status' => [
                'unread' => ContactMessage::unread()->count(),
                'read' => ContactMessage::read()->count(),
                'replied' => ContactMessage::replied()->count(),
                'archived' => ContactMessage::byStatus('archived')->count(),
            ],
        ];

        return response()->json($stats);
    }
} 