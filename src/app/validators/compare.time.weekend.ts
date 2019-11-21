import { AbstractControl } from '@angular/forms';

export function CompareTime(control: AbstractControl) {
    let start, startHoliday;
    let end, endHoliday;
    if (!control.get('weekday_start_time').value || !control.get('weekday_end_time').value
        || !control.get('holiday_start_time').value || !control.get('holiday_end_time').value) {
        return null;
    }

    start = new Date(control.get('weekday_start_time').value).getTime();
    end = new Date(control.get('weekday_end_time').value).getTime();
    startHoliday = new Date(control.get('holiday_start_time').value).getTime();
    endHoliday = new Date(control.get('holiday_end_time').value).getTime();

    if ((end - start) < 3600000 && (endHoliday - startHoliday) < 3600000) {
        return { invalidTimeCommon: true };
    }

    if ((end - start) < 3600000) {
        return { invalidTimeWeekend: true };
    }

    if ((endHoliday - startHoliday) < 3600000) {
        return { invalidTimeHoliday: true };
    }

    return null;
}


