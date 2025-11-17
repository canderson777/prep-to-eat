@props([
    'eyebrow' => null,
    'title' => '',
    'description' => null,
])

<section {{ $attributes->merge(['class' => 'relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-white']) }}>
    <div class="absolute inset-x-0 top-0 -z-10 h-64 bg-gradient-to-b from-emerald-200/40 via-transparent to-transparent"></div>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:py-20 lg:px-8">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div class="lg:w-2/3">
                @if($eyebrow)
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-700 shadow ring-1 ring-emerald-200/80">
                        {{ $eyebrow }}
                    </span>
                @endif
                <h1 class="mt-6 text-3xl font-bold text-slate-900 sm:text-4xl lg:text-5xl">
                    {{ $title }}
                </h1>
                @if($description)
                    <p class="mt-4 text-base text-slate-700 sm:text-lg">
                        {{ $description }}
                    </p>
                @endif
                @isset($actions)
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        {{ $actions }}
                    </div>
                @endisset
            </div>

            @if(! $slot->isEmpty())
                <div class="lg:w-1/3">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</section>

