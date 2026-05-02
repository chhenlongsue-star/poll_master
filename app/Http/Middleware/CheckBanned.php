public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->is_banned) {
        // 1. Log the user out of the application
        auth()->logout();

        // 2. Clear all session data for this user
        $request->session()->invalidate();

        // 3. Prevent CSRF attacks by regenerating the token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', 'Your account has been disabled.');
    }

    return $next($request);
}