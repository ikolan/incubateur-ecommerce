/**
 * Format a given date.
 */
export function formatDateString(dateString) {
    return new Date(dateString).toLocaleDateString('fr');
}

/**
 * Format a given date & time.
 */
export function formatDateTimeString(dateString) {
    return new Date(dateString).toLocaleString('fr');
}
