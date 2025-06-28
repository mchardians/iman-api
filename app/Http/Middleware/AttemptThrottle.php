<?php
namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class AttemptThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts  Maximum attempts allowed
     * @param  int  $decayMinutes  Time window in minutes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $key = $this->getThrottleKey($request);
        $currentAttempts = Cache::get($key, 0);

        if($currentAttempts >= $maxAttempts) {
            return ApiResponse::error(
                "Too many attempts. Please try again after {$decayMinutes} minutes!",
                "Too many requests", 429
            );
        }

        $response = $next($request);

        if($this->isSuccessfulResponse($response)) {
            $newAttempts = $currentAttempts + 1;
            $expiresAt = now()->addMinutes($decayMinutes);
            Cache::put($key, $newAttempts, $expiresAt);

            $remainingAttempts = max(0, $maxAttempts - $newAttempts);

            $response->headers->add([
                'X-Throttle-Attempts' => $newAttempts,
                'X-Throttle-Max-Attempts' => $maxAttempts,
                'X-Throttle-Remaining' => $remainingAttempts,
                'X-Throttle-Reset-Time' => $expiresAt->timestamp,
                'X-Throttle-Window-Minutes' => $decayMinutes
            ]);

            if($this->isJsonResponse($response)) {
                $this->addThrottleInfoToResponse($response, $newAttempts, $maxAttempts, $remainingAttempts, $decayMinutes, $expiresAt);
            }
        }

        return $response;
    }

    /**
     * Check ifresponse is JSON
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    private function isJsonResponse(Response $response): bool
    {
        $contentType = $response->headers->get('content-type', '');
        return str_contains($contentType, 'application/json') || str_contains($contentType, 'json');
    }

    /**
     * Add throttle info to existing JSON response inside 'data' key
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  int  $attempts
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int  $decayMinutes
     * @param  \Carbon\Carbon  $expiresAt
     * @return void
     */
    private function addThrottleInfoToResponse(Response $response, int $attempts, int $maxAttempts, int $remainingAttempts, int $decayMinutes, \Carbon\Carbon $expiresAt): void
    {
        $content = $response->getContent();

        $data = json_decode($content, true);

        if(json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            if(!isset($data['data'])) {
                $data['data'] = [];
            }

            $data['data']['throttle_info'] = [
                'attempts' => $attempts,
                'max_attempts' => $maxAttempts,
                'remaining_attempts' => $remainingAttempts,
                'window_minutes' => $decayMinutes,
                'reset_time' => $expiresAt->format('Y-m-d H:i:s')
            ];

            $response->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Check ifresponse is successful (2xx status codes)
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    private function isSuccessfulResponse(Response $response): bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    /**
     * Generate unique throttle key based on IP
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function getThrottleKey(Request $request): string
    {
        return 'throttle_ip_' . str_replace('.', '_', $request->ip());
    }
}