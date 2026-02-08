<div {{ $attributes }}>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Progressbar</h5>
        </div>
        <div class="card-body">
            <div class="progress" role="progressbar">
                <div class="progress-bar" style="width: 75%;">75%</div>
            </div>
        </div>
    </div>
    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title">Circle progress</h5>
        </div>
        <div class="card-body">
            <div x-data="{ progress: 65 }" class="flex items-center justify-center">
                <svg class="w-32 h-32 transform -rotate-90">
                    <!-- Background circle -->
                    <circle
                        class="text-gray-300"
                        stroke="currentColor"
                        stroke-width="8"
                        fill="transparent"
                        r="50"
                        cx="64"
                        cy="64" />
                    <!-- Progress circle -->
                    <circle
                        class="text-blue-500"
                        stroke="currentColor"
                        stroke-width="8"
                        fill="transparent"
                        r="50"
                        cx="64"
                        cy="64"
                        :stroke-dasharray="2 * Math.PI * 50"
                        :stroke-dashoffset="2 * Math.PI * 50 - (progress / 100) * (2 * Math.PI * 50)" />
                </svg>
                <span class="absolute text-xl font-bold" x-text="progress + '%'"></span>
            </div>

        </div>
    </div>
</div>
