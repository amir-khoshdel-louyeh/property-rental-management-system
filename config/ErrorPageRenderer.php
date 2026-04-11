<?php

class ErrorPageRenderer {
    public static function render($statusCode, $message, $requestId = '') {
        $meta = self::statusMeta($statusCode);
        $safeMessage = htmlspecialchars($message !== '' ? $message : $meta['defaultMessage'], ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($meta['title'], ENT_QUOTES, 'UTF-8');
        $safeHint = htmlspecialchars($meta['hint'], ENT_QUOTES, 'UTF-8');
        $safeRequestId = htmlspecialchars($requestId, ENT_QUOTES, 'UTF-8');

        echo '<!doctype html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<meta charset="utf-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<title>' . $statusCode . ' ' . $safeTitle . '</title>';
        echo '<style>';
        echo 'body{margin:0;font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(135deg,#edf2f7 0%,#d9e2ec 100%);color:#1f2933;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;}';
        echo '.card{max-width:760px;width:100%;background:#fff;border-radius:16px;box-shadow:0 12px 30px rgba(15,23,42,.15);overflow:hidden;}';
        echo '.top{padding:24px 28px;background:linear-gradient(135deg,#0052cc 0%,#2684ff 100%);color:#fff;}';
        echo '.code{display:inline-block;font-size:13px;letter-spacing:.08em;background:rgba(255,255,255,.2);padding:6px 10px;border-radius:999px;margin-bottom:10px;}';
        echo '.title{font-size:28px;font-weight:700;margin:0;}';
        echo '.body{padding:28px;}';
        echo '.message{font-size:18px;line-height:1.5;margin:0 0 12px;}';
        echo '.hint{font-size:15px;color:#52606d;margin:0 0 20px;}';
        echo '.actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;}';
        echo '.btn{display:inline-block;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600;font-size:14px;}';
        echo '.btn-primary{background:#0052cc;color:#fff;}';
        echo '.btn-secondary{background:#eef2f7;color:#102a43;}';
        echo '.meta{margin-top:20px;padding-top:14px;border-top:1px solid #e4e7eb;color:#7b8794;font-size:12px;}';
        echo '@media (max-width:680px){.title{font-size:22px}.message{font-size:16px}.top,.body{padding:20px}}';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<main class="card" role="main" aria-live="polite">';
        echo '<section class="top">';
        echo '<span class="code">Error ' . $statusCode . '</span>';
        echo '<h1 class="title">' . $safeTitle . '</h1>';
        echo '</section>';
        echo '<section class="body">';
        echo '<p class="message">' . $safeMessage . '</p>';
        echo '<p class="hint">' . $safeHint . '</p>';
        echo '<div class="actions">';
        echo '<a class="btn btn-primary" href="index.php">Go To Home</a>';
        echo '<a class="btn btn-secondary" href="javascript:history.back()">Go Back</a>';
        echo '</div>';
        if ($safeRequestId !== '') {
            echo '<div class="meta">Request ID: ' . $safeRequestId . '</div>';
        }
        echo '</section>';
        echo '</main>';
        echo '</body>';
        echo '</html>';
    }

    private static function statusMeta($statusCode) {
        $map = [
            400 => [
                'title' => 'Bad Request',
                'defaultMessage' => 'The request could not be understood by the server.',
                'hint' => 'Please review your input and try again.'
            ],
            401 => [
                'title' => 'Unauthorized',
                'defaultMessage' => 'You need to sign in to continue.',
                'hint' => 'Log in and try the action again.'
            ],
            403 => [
                'title' => 'Access Denied',
                'defaultMessage' => 'You do not have permission to access this page.',
                'hint' => 'If this seems wrong, contact an administrator.'
            ],
            404 => [
                'title' => 'Page Not Found',
                'defaultMessage' => 'The page or resource you requested does not exist.',
                'hint' => 'Check the URL or return to the home page.'
            ],
            405 => [
                'title' => 'Method Not Allowed',
                'defaultMessage' => 'This action is not allowed for this endpoint.',
                'hint' => 'Try using the correct action and submit again.'
            ],
            422 => [
                'title' => 'Validation Error',
                'defaultMessage' => 'Some submitted values are invalid.',
                'hint' => 'Fix highlighted fields and submit again.'
            ],
            429 => [
                'title' => 'Too Many Requests',
                'defaultMessage' => 'You have made too many requests in a short time.',
                'hint' => 'Wait a moment before retrying.'
            ],
            500 => [
                'title' => 'Server Error',
                'defaultMessage' => 'Something went wrong on our side.',
                'hint' => 'Please try again in a moment.'
            ]
        ];

        return $map[$statusCode] ?? [
            'title' => 'Unexpected Error',
            'defaultMessage' => 'An unexpected problem occurred.',
            'hint' => 'Please try again or contact support if it continues.'
        ];
    }
}
