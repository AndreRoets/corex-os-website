/**
 * South African time, client-side mirror of App\Support\Sast.
 *
 * CoreX always sends timestamps with an explicit +02:00 offset (SAST has no
 * daylight saving), so the wall-clock date and time are exactly the first 16
 * characters of the ISO string — no timezone conversion needed, just slicing.
 */

const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

export function dateForInput(iso) {
    return iso ? iso.slice(0, 10) : '';
}

export function timeForInput(iso) {
    return iso ? iso.slice(11, 16) : '';
}

export function endTimeForInput(startIso, durationMinutes) {
    const start = timeForInput(startIso);
    if (! start || durationMinutes == null || durationMinutes === '') return '';

    const [h, m] = start.split(':').map(Number);
    const total = (h * 60 + m + Number(durationMinutes) + 24 * 60) % (24 * 60);

    return `${String(Math.floor(total / 60)).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`;
}

export function timeLabel(time) {
    const [hour, minute] = time.split(':').map(Number);
    const clock = `${hour % 12 === 0 ? 12 : hour % 12}:${String(minute).padStart(2, '0')}`;

    if (hour === 0 && minute === 0) return `${clock} midnight`;
    if (hour === 12 && minute === 0) return `${clock} noon`;

    return `${clock} ${hour < 12 ? 'am' : 'pm'}`;
}

export function timeChoices(include) {
    const values = [];
    for (let minutes = 0; minutes < 24 * 60; minutes += 15) {
        values.push(`${String(Math.floor(minutes / 60)).padStart(2, '0')}:${String(minutes % 60).padStart(2, '0')}`);
    }

    if (include && ! values.includes(include)) {
        values.push(include);
        values.sort();
    }

    return values.map((value) => [value, timeLabel(value)]);
}

export function short(iso) {
    if (! iso) return null;

    const date = dateForInput(iso);
    const time = timeForInput(iso);
    if (! date) return null;

    const [y, m, d] = date.split('-').map(Number);

    return `${d} ${MONTHS[m - 1]} ${y}, ${time}`;
}

export function longDate(iso) {
    if (! iso) return null;

    const date = dateForInput(iso);
    const [y, m, d] = date.split('-').map(Number);

    return `${d} ${MONTHS[m - 1]} ${y}`;
}
