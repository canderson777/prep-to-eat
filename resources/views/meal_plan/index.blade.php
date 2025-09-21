<!DOCTYPE html>
<html>
@php use Illuminate\Support\Str; @endphp
<head>
    <title>Meal Plan | PrepToEat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background: #f8fafc; font-family: Arial, sans-serif; margin: 0; }
        .container { max-width: 1000px; margin: 30px auto; background: #fff; border-radius: 14px; box-shadow: 0 4px 20px #e3f5ff; padding: 32px 28px 40px 28px; }
        h1 { text-align: center; color: #2563eb; margin-bottom: 18px; }
        .nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 26px; flex-wrap: wrap; }
        .nav a { background: #38b6ff; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; transition: background .2s; }
        .nav a:hover { background: #109cff; }
        .grid { display: grid; gap: 22px; }
        .weekly, .monthly, .create { background: #f0f9ff; border: 1px solid #cbe9ff; border-radius: 10px; padding: 20px; }
        h2 { margin-top: 0; color: #1d4ed8; }
        .week-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
        .week-day { background: #fff; border: 1px solid #dbeafe; border-radius: 8px; padding: 12px; min-height: 140px; display: flex; flex-direction: column; gap: 8px; }
        .week-day strong { color: #1e3a8a; }
        .entry { background: #eff6ff; border-radius: 6px; padding: 8px; font-size: 0.92em; display: flex; justify-content: space-between; gap: 6px; }
        .entry a { color: #2563eb; text-decoration: none; }
        .entry small { color: #64748b; display: block; }
        .entry form { margin: 0; }
        .entry button { background: transparent; border: none; color: #dc2626; cursor: pointer; font-size: 0.85em; }
        .create form { display: grid; gap: 12px; }
        select, input[type="date"], textarea { padding: 10px; border: 1px solid #bfdbfe; border-radius: 6px; }
        textarea { min-height: 70px; resize: vertical; }
        .submit-btn { background: #22c55e; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; justify-self: start; }
        .submit-btn:hover { background: #16a34a; }
        table.calendar { width: 100%; border-collapse: collapse; }
        table.calendar th, table.calendar td { border: 1px solid #bfdbfe; padding: 12px 10px; vertical-align: top; min-height: 120px; width: 14.28%; }
        table.calendar th { background: #dbeafe; color: #1e3a8a; }
        table.calendar td { background: #fff; }
        .calendar-date { font-weight: bold; color: #1d4ed8; margin-bottom: 6px; }
        .calendar-entry { background: #f8fafc; border-left: 3px solid #38b6ff; border-radius: 6px; padding: 6px 8px; margin-bottom: 6px; font-size: 0.9em; }
        .calendar-entry a { color: #0f172a; text-decoration: none; }
        @media (max-width: 768px) {
            .container { padding: 18px 14px 26px 14px; }
            table.calendar th, table.calendar td { font-size: 0.88em; padding: 10px 6px; }
            .week-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ route('recipes.index') }}">My Recipes</a>
        </div>
        <h1>Meal Planner</h1>
        <div class="grid">
            <div class="create">
                <h2>Plan a Meal</h2>
                <form action="{{ route('meal-plan.store') }}" method="POST">
                    @csrf
                    <label for="saved_recipe_id" style="font-weight:bold; color:#1e3a8a;">Recipe</label>
                    <select id="saved_recipe_id" name="saved_recipe_id" required>
                        <option value="">Select a recipe</option>
                        @foreach($recipes as $recipe)
                            <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                        @endforeach
                    </select>
                    <label for="planned_for" style="font-weight:bold; color:#1e3a8a;">Date</label>
                    <input type="date" id="planned_for" name="planned_for" required>
                    <label for="meal_type" style="font-weight:bold; color:#1e3a8a;">Meal slot</label>
                    <select id="meal_type" name="meal_type">
                        <option value="">Select meal slot</option>
                        <option value="Breakfast">Breakfast</option>
                        <option value="Lunch">Lunch</option>
                        <option value="Dinner">Dinner</option>
                        <option value="Snack">Snack</option>
                    </select>
                    <label for="notes" style="font-weight:bold; color:#1e3a8a;">Notes</label>
                    <textarea id="notes" name="notes" placeholder="Add prep reminders or grocery items..."></textarea>
                    <button type="submit" class="submit-btn">Add to Plan</button>
                </form>
            </div>

            <div class="weekly">
                <h2>This Week</h2>
                <div class="week-row">
                    @foreach($weekPeriod as $day)
                        @php
                            $dateKey = $day->toDateString();
                            $dayEntries = $weekEntries[$dateKey] ?? collect();
                        @endphp
                        <div class="week-day">
                            <strong>{{ $day->format('D, M j') }}</strong>
                            @forelse($dayEntries as $entry)
                                <div class="entry">
                                    <div>
                                        <a href="{{ route('recipes.index') }}#recipe-{{ $entry->saved_recipe_id }}">{{ $entry->recipe?->title }}</a>
                                        <small>{{ $entry->meal_type ?? 'Anytime' }}</small>
                                    </div>
                                    <form action="{{ route('meal-plan.destroy', $entry) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove">✕</button>
                                    </form>
                                </div>
                            @empty
                                <div style="color:#94a3b8; font-size:0.9em;">No meals planned.</div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="monthly">
                <h2>{{ $currentMonth->format('F Y') }}</h2>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $week = []; @endphp
                        @foreach($monthPeriod as $day)
                            @php
                                $week[] = $day;
                                if (count($week) === 7) {
                                    echo '<tr>';
                                    foreach ($week as $weekDay) {
                                        $dateKey = $weekDay->toDateString();
                                        $dayEntries = $entriesByDate[$dateKey] ?? collect();
                                        $isCurrentMonth = $weekDay->isSameMonth($currentMonth);
                                        echo '<td'.($isCurrentMonth ? '' : ' style="background:#f8fafc;color:#94a3b8;"').'>';
                                        echo '<div class="calendar-date">'.$weekDay->format('j').'</div>';
                                        foreach ($dayEntries as $calendarEntry) {
                                            $title = e($calendarEntry->recipe?->title ?? 'Recipe removed');
                                            $link = route('recipes.index').'#recipe-'.$calendarEntry->saved_recipe_id;
                                            echo '<div class="calendar-entry">';
                                            echo '<a href="'.$link.'">'.$title.'</a>';
                                            if ($calendarEntry->meal_type) {
                                                echo '<div style="color:#475569;font-size:0.8em;">'.$calendarEntry->meal_type.'</div>';
                                            }
                                            if ($calendarEntry->notes) {
                                                echo '<div style="color:#0f172a;font-size:0.78em;">'.e(Str::limit($calendarEntry->notes, 60)).'</div>';
                                            }
                                            echo '</div>';
                                        }
                                        echo '</td>';
                                    }
                                    echo '</tr>';
                                    $week = [];
                                }
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
