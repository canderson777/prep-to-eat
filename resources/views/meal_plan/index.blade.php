@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $weekDays = iterator_to_array($weekPeriod);
    $monthDays = iterator_to_array($monthPeriod);

    $weekMealCount = $weekEntries->reduce(fn ($carry, $entries) => $carry + $entries->count(), 0);
    $monthMealCount = $entriesByDate->reduce(fn ($carry, $entries) => $carry + $entries->count(), 0);
@endphp

@section('content')
    <x-page-hero
        eyebrow="Meal Planner"
        title="Intentional weekly planning for vibrant eating"
        description="Drag-free scheduling that keeps your favorite recipes, grocery prep notes, and wellness goals aligned."
    >
        <x-slot:actions>
            <a href="{{ route('recipes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-700">
                <i class="fa-solid fa-book-open"></i>
                Visit My Kitchen
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-700 shadow ring-1 ring-emerald-100 transition hover:bg-emerald-50">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                Generate a recipe
            </a>
        </x-slot:actions>

        <div class="rounded-3xl bg-white/90 p-6 shadow-xl ring-1 ring-emerald-100/80">
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Planning snapshot</p>
            <dl class="mt-4 grid gap-4 text-sm text-slate-600 sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Meals this week</dt>
                    <dd class="mt-2 text-2xl font-bold text-slate-900">{{ $weekMealCount }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">{{ $currentMonth->format('F') }} scheduled</dt>
                    <dd class="mt-2 text-2xl font-bold text-slate-900">{{ $monthMealCount }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-widest text-emerald-500">Cookbook available</dt>
                    <dd class="mt-2 text-2xl font-bold text-slate-900">{{ $recipes->count() }}</dd>
                </div>
            </dl>
        </div>
    </x-page-hero>

    <section class="bg-white/80">
        <div class="mx-auto max-w-7xl space-y-10 px-4 py-10 sm:px-6 lg:py-16 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-900 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1fr,1.2fr]">
                <div class="space-y-8">
                    <div class="rounded-3xl bg-white/90 p-6 shadow-xl ring-1 ring-emerald-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Plan a meal</p>
                                <h2 class="text-2xl font-semibold text-slate-900">Add to your calendar</h2>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase text-emerald-700">{{ $recipes->count() }} recipes</span>
                        </div>
                        <form action="{{ route('meal-plan.store') }}" method="POST" class="mt-6 space-y-5">
                            @csrf
                            <div>
                                <label for="saved_recipe_id" class="text-sm font-semibold text-slate-700">Recipe</label>
                                <select id="saved_recipe_id" name="saved_recipe_id" {{ $recipes->isEmpty() ? 'disabled' : '' }} class="mt-2 w-full rounded-2xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                    <option value="">Select a recipe</option>
                                    @foreach($recipes as $recipe)
                                        <option value="{{ $recipe->id }}" {{ (string) old('saved_recipe_id') === (string) $recipe->id ? 'selected' : '' }}>
                                            {{ $recipe->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($recipes->isEmpty())
                                    <p class="mt-2 text-xs text-slate-500">Save a recipe in My Kitchen to start planning.</p>
                                @endif
                                @error('saved_recipe_id')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label for="planned_for" class="text-sm font-semibold text-slate-700">Date</label>
                                    <input type="date" id="planned_for" name="planned_for" value="{{ old('planned_for') }}" required class="mt-2 w-full rounded-2xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                    @error('planned_for')
                                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="meal_type" class="text-sm font-semibold text-slate-700">Meal slot</label>
                                    <select id="meal_type" name="meal_type" class="mt-2 w-full rounded-2xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                                        <option value="">Anytime</option>
                                        <option value="Breakfast" {{ old('meal_type') === 'Breakfast' ? 'selected' : '' }}>Breakfast</option>
                                        <option value="Lunch" {{ old('meal_type') === 'Lunch' ? 'selected' : '' }}>Lunch</option>
                                        <option value="Dinner" {{ old('meal_type') === 'Dinner' ? 'selected' : '' }}>Dinner</option>
                                        <option value="Snack" {{ old('meal_type') === 'Snack' ? 'selected' : '' }}>Snack</option>
                                    </select>
                                    @error('meal_type')
                                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label for="notes" class="text-sm font-semibold text-slate-700">Prep notes</label>
                                <textarea id="notes" name="notes" rows="3" class="mt-2 w-full rounded-2xl border border-emerald-100 bg-white px-4 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100" placeholder="Double batch for lunch, marinate overnight, thaw broth...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" {{ $recipes->isEmpty() ? 'disabled' : '' }} class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-slate-300">
                                <i class="fa-solid fa-calendar-plus"></i>
                                Add to plan
                            </button>
                        </form>
                    </div>

                    <div class="rounded-3xl bg-emerald-50/70 p-6 shadow ring-1 ring-emerald-100">
                        <h3 class="text-lg font-semibold text-emerald-900">Weekly focus</h3>
                        <ul class="mt-4 space-y-3 text-sm text-emerald-800">
                            <li class="flex items-start gap-3"><i class="fa-solid fa-circle-check text-emerald-500"></i>Balance protein-rich meals with light lunches to maintain energy.</li>
                            <li class="flex items-start gap-3"><i class="fa-solid fa-circle-check text-emerald-500"></i>Batch cook grains and roasted veggies to streamline dinners.</li>
                            <li class="flex items-start gap-3"><i class="fa-solid fa-circle-check text-emerald-500"></i>Use prep notes to remind yourself of overnight steps or thawing.</li>
                        </ul>
                    </div>
                </div>

                <div class="rounded-3xl bg-white/90 p-6 shadow-2xl ring-1 ring-emerald-100">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">This week</p>
                            <h2 class="text-2xl font-semibold text-slate-900">Monday–Sunday snapshot</h2>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ $weekDays[0]->format('M j') }} – {{ end($weekDays)->format('M j') }}</span>
                    </div>
                    <div class="mt-6 grid gap-4 md:grid-cols-2">
                        @foreach($weekDays as $day)
                            @php
                                $dateKey = $day->toDateString();
                                $entriesForDay = $weekEntries[$dateKey] ?? collect();
                            @endphp
                            <div class="rounded-2xl border border-emerald-50 bg-white/70 p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500">{{ $day->format('D') }}</p>
                                        <p class="text-lg font-semibold text-slate-900">{{ $day->format('M j') }}</p>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-400">{{ $entriesForDay->count() }} meals</span>
                                </div>
                                <div class="mt-4 space-y-3">
                                    @forelse($entriesForDay as $entry)
                                        <div class="rounded-2xl bg-emerald-50/80 p-3 text-sm text-slate-700">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <a href="{{ route('recipes.index') }}#recipe-{{ $entry->saved_recipe_id }}" class="font-semibold text-emerald-800 hover:text-emerald-600">
                                                        {{ $entry->recipe?->title ?? 'Recipe removed' }}
                                                    </a>
                                                    <p class="text-xs text-slate-500">{{ $entry->meal_type ?? 'Anytime' }}</p>
                                                    @if($entry->notes)
                                                        <p class="mt-1 text-xs text-slate-500">{{ Str::limit($entry->notes, 80) }}</p>
                                                    @endif
                                                </div>
                                                <form action="{{ route('meal-plan.destroy', $entry) }}" method="POST" class="shrink-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-full bg-white px-2 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400">No meals planned yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white/90 p-6 shadow-2xl ring-1 ring-emerald-100">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-widest text-emerald-500">Monthly calendar</p>
                        <h2 class="text-2xl font-semibold text-slate-900">{{ $currentMonth->format('F Y') }}</h2>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-emerald-200"></span> Scheduled</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-slate-100"></span> Other month</span>
                    </div>
                </div>

                @php
                    $monthWeeks = array_chunk($monthDays, 7);
                @endphp

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-widest text-slate-500">
                                <th class="px-3 py-2">Sun</th>
                                <th class="px-3 py-2">Mon</th>
                                <th class="px-3 py-2">Tue</th>
                                <th class="px-3 py-2">Wed</th>
                                <th class="px-3 py-2">Thu</th>
                                <th class="px-3 py-2">Fri</th>
                                <th class="px-3 py-2">Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthWeeks as $week)
                                <tr>
                                    @foreach($week as $weekDay)
                                        @php
                                            $dateKey = $weekDay->toDateString();
                                            $dayEntries = $entriesByDate[$dateKey] ?? collect();
                                            $isCurrentMonth = $weekDay->isSameMonth($currentMonth);
                                        @endphp
                                        <td class="align-top border border-emerald-50 px-3 py-3 {{ $isCurrentMonth ? 'bg-white' : 'bg-slate-50' }}">
                                            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                                <span class="{{ $isCurrentMonth ? 'text-slate-900' : 'text-slate-400' }}">{{ $weekDay->format('j') }}</span>
                                                @if($dayEntries->isNotEmpty())
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">{{ $dayEntries->count() }}</span>
                                                @endif
                                            </div>
                                            <div class="mt-2 space-y-2">
                                                @foreach($dayEntries as $calendarEntry)
                                                    <div class="rounded-2xl bg-emerald-50/80 p-2 text-xs text-slate-700">
                                                        <a href="{{ route('recipes.index') }}#recipe-{{ $calendarEntry->saved_recipe_id }}" class="font-semibold text-emerald-800 hover:text-emerald-600">
                                                            {{ $calendarEntry->recipe?->title ?? 'Recipe removed' }}
                                                        </a>
                                                        @if($calendarEntry->meal_type)
                                                            <p class="text-[11px] text-slate-500">{{ $calendarEntry->meal_type }}</p>
                                                        @endif
                                                        @if($calendarEntry->notes)
                                                            <p class="mt-1 text-[11px] text-slate-500">{{ Str::limit($calendarEntry->notes, 80) }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

