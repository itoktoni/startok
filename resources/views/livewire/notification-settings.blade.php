<div class="max-w-2xl mx-auto">
    <x-card>
        <x-slot:title>
            <div class="flex items-center gap-2">
                <span class="icon-[tabler--bell] size-5"></span>
                Push Notification Settings
            </div>
        </x-slot:title>

        @if (session('status'))
            <div class="alert alert-success mb-4">
                <span class="icon-[tabler--circle-check] size-5"></span>
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-base-200 rounded-lg">
                <div>
                    <h3 class="font-semibold">Push Notifications</h3>
                    <p class="text-sm text-base-content/70">
                        Receive notifications for new orders, updates, and important alerts.
                    </p>
                </div>
                <div x-data="{
                    subscribed: @js($isSubscribed),
                    loading: false,
                    async toggle() {
                        this.loading = true;
                        try {
                            if (this.subscribed) {
                                await PushNotification.unsubscribe();
                                this.subscribed = false;
                            } else {
                                await PushNotification.subscribe();
                                this.subscribed = true;
                            }
                            @this.toggleSubscription();
                        } catch (e) {
                            alert(e.message || 'Failed to update notification settings');
                        } finally {
                            this.loading = false;
                        }
                    }
                }">
                    <button
                        @click="toggle()"
                        :disabled="loading"
                        class="btn btn-sm"
                        :class="subscribed ? 'btn-error' : 'btn-primary'"
                    >
                        <span x-show="loading" class="loading loading-spinner loading-xs"></span>
                        <span x-show="!loading && !subscribed" class="icon-[tabler--bell] size-4"></span>
                        <span x-show="!loading && subscribed" class="icon-[tabler--bell-off] size-4"></span>
                        <span x-text="loading ? 'Updating...' : (subscribed ? 'Disable' : 'Enable')"></span>
                    </button>
                </div>
            </div>

            <div class="divider"></div>

            <div class="space-y-4">
                <h3 class="font-semibold">How it works</h3>
                <ul class="space-y-2 text-sm text-base-content/70">
                    <li class="flex items-start gap-2">
                        <span class="icon-[tabler--circle-check] size-4 text-success mt-0.5"></span>
                        <span>Allow browser notifications when prompted</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="icon-[tabler--circle-check] size-4 text-success mt-0.5"></span>
                        <span>You'll receive notifications even when the app is closed</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="icon-[tabler--circle-check] size-4 text-success mt-0.5"></span>
                        <span>Works on desktop (Chrome, Firefox, Edge, Safari) and mobile (Android)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="icon-[tabler--info-circle] size-4 text-info mt-0.5"></span>
                        <span>iOS (iPhone/iPad) requires adding the app to Home Screen first</span>
                    </li>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="space-y-4">
                <h3 class="font-semibold">Browser Support</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                        <span class="icon-[tabler--brand-chrome] size-5"></span>
                        <span class="text-sm">Chrome 50+</span>
                        <span class="badge badge-success badge-xs">Supported</span>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                        <span class="icon-[tabler--brand-firefox] size-5"></span>
                        <span class="text-sm">Firefox 44+</span>
                        <span class="badge badge-success badge-xs">Supported</span>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                        <span class="icon-[tabler--brand-edge] size-5"></span>
                        <span class="text-sm">Edge 17+</span>
                        <span class="badge badge-success badge-xs">Supported</span>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-base-200 rounded-lg">
                        <span class="icon-[tabler--brand-safari] size-5"></span>
                        <span class="text-sm">Safari 16+</span>
                        <span class="badge badge-warning badge-xs">Partial</span>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
