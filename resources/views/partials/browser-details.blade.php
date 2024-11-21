<div class="container my-4">
    <div class="row g-4">
        @forelse($browsers as $browser)
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title d-flex align-items-center mb-3">
                            <i class="fas fa-browser me-2 text-primary"></i>
                            {{ $browser->browser }}
                        </h6>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-calendar-check me-1"></i>
                            Verified At: {{ $browser->verified_at->format('d M Y, h:i A') }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt me-1 text-secondary"></i>
                            <strong>IP:</strong> {{ $browser->session_ip }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-clock me-1 text-info"></i>
                            <strong>Expires:</strong>
                            <span class="badge {{ $browser->expires_at && now()->lessThan($browser->expires_at) ? 'bg-success' : 'bg-danger' }}">
                                {{ $browser->expires_at ? $browser->expires_at->format('d M Y, h:i A') : 'No Expiry' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <span class="text-muted fst-italic">
                    <i class="fas fa-info-circle me-1"></i>
                    No verified browsers
                </span>
            </div>
        @endforelse
    </div>
</div>
