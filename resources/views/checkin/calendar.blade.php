<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-gray-200">Daily Mood Check-In Calendar</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-3 mb-6 rounded-lg shadow-md">
                {{ session('status') }}
            </div>
        @endif

        <!-- Month Navigation -->
        <div class="flex justify-between items-center mb-6">
            <button onclick="changeMonth(-1)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">Previous</button>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h2>
            <button onclick="changeMonth(1)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">Next</button>
        </div>

        @php
            $firstDay = (clone $start)->startOfWeek();
            $lastDay = (clone $end)->endOfWeek();
            $days = new DatePeriod($firstDay, new DateInterval('P1D'), $lastDay->addDay());
            $moods = ['happy' => '😊', 'neutral' => '😐', 'sad' => '😢', 'worried' => '😟', 'angry' => '😠', 'sleepy' => '😴', 'love' => '😍'];
            $moodColors = ['happy' => 'bg-green-200 hover:bg-green-300', 'neutral' => 'bg-yellow-200 hover:bg-yellow-300', 'sad' => 'bg-red-200 hover:bg-red-300', 'worried' => 'bg-orange-200 hover:bg-orange-300', 'angry' => 'bg-red-300 hover:bg-red-400', 'sleepy' => 'bg-blue-200 hover:bg-blue-300', 'love' => 'bg-pink-200 hover:bg-pink-300'];
            $selectedDate = request('date', \Carbon\Carbon::today()->format('Y-m-d'));
            $selectedCheckIn = $checkIns[$selectedDate] ?? null;
        @endphp

        <!-- Calendar Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-6">
            <div class="grid grid-cols-7 gap-2 text-center text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <div class="grid grid-cols-7 gap-2">
                @foreach ($days as $day)
                    @php
                        $dateStr = $day->format('Y-m-d');
                        $inMonth = $day->format('m') == $month;
                        $ci = $checkIns[$dateStr] ?? null;
                        $isToday = $day->format('Y-m-d') == \Carbon\Carbon::today()->format('Y-m-d');
                        $isSelected = $dateStr == $selectedDate;
                        $moodColor = $ci ? ($moodColors[$ci->mood] ?? 'bg-gray-200 hover:bg-gray-300') : 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600';
                    @endphp
                    <div class="border rounded-lg p-3 h-20 flex flex-col items-center justify-center {{ $inMonth ? 'cursor-pointer transition duration-200 ' . $moodColor : 'bg-gray-200 text-gray-400 dark:bg-gray-600' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }} {{ $isSelected ? 'ring-4 ring-indigo-500' : '' }}" {{ $inMonth ? 'onclick="selectDate(\'' . $dateStr . '\')"' : '' }} title="{{ $ci ? 'Mood: ' . ucfirst($ci->mood) . ($ci->note ? ' - ' . Str::limit($ci->note, 20) : '') : 'No check-in' }}">
                        <div class="text-sm font-semibold {{ $inMonth ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400' }}">{{ $day->format('d') }}</div>
                        @if ($ci)
                            <div class="text-2xl mt-1">{{ $moods[$ci->mood] ?? '•' }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Check-In Form (Collapsible) -->
        <div id="checkin-form" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg {{ $selectedCheckIn ? '' : 'hidden' }}">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Check-In for {{ \Carbon\Carbon::parse($selectedDate)->format('M j, Y') }}</h2>
            <form method="POST" action="{{ route('checkin.store') }}">
                @csrf
                <input type="hidden" name="date" id="selected-date" value="{{ $selectedDate }}">

                <!-- Mood Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">How are you feeling?</label>
                    <div class="grid grid-cols-4 md:grid-cols-7 gap-4">
                        @foreach ($moods as $key => $emoji)
                            <label class="flex flex-col items-center cursor-pointer p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200 {{ ($selectedCheckIn && $selectedCheckIn->mood == $key) ? 'bg-indigo-100 dark:bg-indigo-900' : '' }}">
                                <input type="radio" name="mood" value="{{ $key }}" class="hidden" {{ ($selectedCheckIn && $selectedCheckIn->mood == $key) ? 'checked' : '' }} required>
                                <span class="text-3xl mb-1">{{ $emoji }}</span>
                                <span class="text-xs capitalize font-medium">{{ $key }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (optional)</label>
                    <textarea id="note" name="note" rows="4" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg shadow-sm p-3" placeholder="How was your day? Any thoughts you'd like to share?">{{ $selectedCheckIn->note ?? '' }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">Update Check-In</button>
                </div>
            </form>
        </div>

        <!-- Mood Trends Summary -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mt-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Mood Trends (Last 30 Days)</h3>
            @php
                $recentCheckIns = collect($checkIns)->where('date', '>=', \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'))->sortBy('date');
                $moodCounts = $recentCheckIns->pluck('mood')->countBy();
            @endphp
            @if ($recentCheckIns->isNotEmpty())
                <div class="grid grid-cols-7 gap-4 mb-4">
                    @foreach ($moods as $key => $emoji)
                        <div class="text-center p-3 rounded-lg {{ $moodColors[$key] ?? 'bg-gray-200' }}">
                            <div class="text-2xl">{{ $emoji }}</div>
                            <div class="text-sm font-medium">{{ $moodCounts[$key] ?? 0 }}</div>
                        </div>
                    @endforeach
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Total check-ins: {{ $recentCheckIns->count() }}</p>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No check-ins in the last 30 days. Start tracking your mood!</p>
            @endif
        </div>
    </div>

    <script>
        const checkIns = @json($checkIns);

        function selectDate(date) {
            document.getElementById('selected-date').value = date;
            const ci = checkIns[date];
            const form = document.getElementById('checkin-form');
            if (ci) {
                form.classList.remove('hidden');
                const moodInput = document.querySelector(`input[name="mood"][value="${ci.mood}"]`);
                if (moodInput) moodInput.checked = true;
                document.getElementById('note').value = ci.note || '';
            } else {
                form.classList.add('hidden');
                document.querySelectorAll('input[name="mood"]').forEach(r => r.checked = false);
                document.getElementById('note').value = '';
            }
            // Update the header
            const dateObj = new Date(date);
            document.querySelector('#checkin-form h2').textContent = 'Check-In for ' + dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }

        function changeMonth(offset) {
            const url = new URL(window.location);
            const currentMonth = new Date(url.searchParams.get('month') || '{{ $month }}-01');
            currentMonth.setMonth(currentMonth.getMonth() + offset);
            url.searchParams.set('month', currentMonth.toISOString().slice(0, 7));
            window.location = url;
        }

        // Initialize with selected date
        selectDate('{{ $selectedDate }}');
    </script>
</x-app-layout>
