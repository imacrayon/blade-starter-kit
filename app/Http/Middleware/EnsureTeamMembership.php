<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Team $team */
        $team = $request->route('team');

        abort_if($request->user()->cannot('view', $team), 403);

        URL::defaults(['team' => $team->slug]);

        return $next($request);
    }
}
