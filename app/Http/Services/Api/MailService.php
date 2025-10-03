<?php

namespace App\Http\Services\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class MailService
{
    /**
     * Send an email immediately.
     *
     * @param array $data Data to pass to the view
     * @param string $to Recipient email address
     * @param string $subjectLine Email subject
     * @param string $viewName Blade view to render
     *
     * @return bool
     */
    public function send(array $data, string|array $to, string $subjectLine = 'Utility', string $viewName = 'welcome'): bool
    {
        try {
            $mail = $this->createMailable($data, $subjectLine, $viewName);

            if (is_array($to)) {
                Mail::to($to)->send($mail);
            } else {
                Mail::to($to)->send($mail);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to " . (is_array($to) ? implode(',', $to) : $to) . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Queue email for asynchronous sending.
     *
     * @param array $data
     * @param string $to
     * @param string $subjectLine
     * @param string $viewName
     * @return bool
     */
    public function sendQueued(array $data, string|array $to, string $subjectLine = 'Utility', string $viewName = 'welcome'): bool
    {
        try {
            $mail = $this->createMailable($data, $subjectLine, $viewName);

            if (is_array($to)) {
                Mail::to($to)->queue($mail);
            } else {
                Mail::to($to)->queue($mail);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to queue email to " . (is_array($to) ? implode(',', $to) : $to) . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a dynamic Mailable instance.
     * 
     * Basically, this little guy wraps up your data, subject, and view into a neat package
     * and hands it over to Mail so you don’t have to wrestle with Mail::send() like it’s 2009.
     * Trust me, your future self (and inbox) will thank you :)
     */
    protected function createMailable(array $data, string $subjectLine, string $viewName): Mailable
    {
        return new class($data, $subjectLine, $viewName) extends Mailable {
            public $data;
            public $subjectLine;
            public $viewName;

            public function __construct(array $data, string $subjectLine, string $viewName)
            {
                $this->data = $data;
                $this->subjectLine = $subjectLine;
                $this->viewName = $viewName;
            }

            public function build()
            {
                return $this->subject($this->subjectLine)
                    ->view($this->viewName)
                    ->with($this->data);
            }
        };
    }
}
