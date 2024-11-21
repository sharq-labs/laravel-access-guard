<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Controllers;

use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Yajra\DataTables\Facades\DataTables;

class VerifiedEmailsController
{
    public function index()
    {
        if (request()->ajax()) {
            $verifiedEmails = UserAccessRecord::with(['browsers' => function ($query) {
                $query->whereNotNull('verified_at'); // Only fetch verified browsers
            }]);

            return DataTables::of($verifiedEmails)
                ->addColumn('email', fn($record) => $record->email)
                ->addColumn('last_verified', fn($record) => $record->last_verified_at ?? 'N/A')
                ->addColumn('browser_details', function ($record) {
                    return view('laravel-access-guard::partials.browser-details', ['browsers' => $record->browsers])->render();
                })
                ->addColumn('status', function ($record) {
                    $activeBrowsers = $record->browsers->filter(function ($browser) {
                        return $browser->expires_at && $browser->expires_at->isFuture();
                    });

                    $expiredBrowsers = $record->browsers->filter(function ($browser) {
                        return $browser->expires_at && $browser->expires_at->isPast();
                    });

                    if ($activeBrowsers->count() > 0) {
                        return '<span class="badge bg-success">Active</span>';
                    } elseif ($expiredBrowsers->count() > 0) {
                        return '<span class="badge bg-danger">Expired</span>';
                    } else {
                        return '<span class="badge bg-warning text-dark">Not Verified</span>';
                    }
                })

                ->filterColumn('email', function ($query, $keyword) {
                    $query->where('email', 'like', "%{$keyword}%");
                })
                ->filterColumn('domain', function ($query, $keyword) {
                    $query->where('domain', 'like', "%{$keyword}%");
                })
                ->filterColumn('browser_details', function ($query, $keyword) {
                    $query->whereHas('browsers', function ($subQuery) use ($keyword) {
                        $subQuery->where('browser', 'like', "%{$keyword}%")
                            ->orWhere('session_ip', 'like', "%{$keyword}%")
                            ->orWhere('expires_at', 'like', "%{$keyword}%")
                            ->orWhere('verified_at', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('status', function ($query, $keyword) {
                    // Check the keyword and apply a filter to the main query before whereHas
                    if (stripos($keyword, 'active') !== false) {
                        // Apply a condition to the main query for 'active' status
                        $query->whereHas('browsers', function ($subQuery) {
                            $subQuery->where('expires_at', '>', now())
                                ->whereNotNull('expires_at');
                        });
                    } elseif (stripos($keyword, 'expired') !== false) {
                        // Apply a condition to the main query for 'expired' status
                        $query->whereHas('browsers', function ($subQuery) {
                            $subQuery->where('expires_at', '<=', now())
                                ->whereNotNull('expires_at');
                        });
                    }
                })

                ->rawColumns(['browser_details', 'status'])
                ->make(true);
        }

        return view('laravel-access-guard::verified-emails');
    }
}
