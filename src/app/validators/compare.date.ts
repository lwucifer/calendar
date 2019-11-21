import { AbstractControl } from '@angular/forms';
declare var $: any;

export function CompareDate(control: AbstractControl) {
    let start_common, end_common, start_opiton, end_option;
    if (!control.get('start_time').value || !control.get('end_time').value) {
        return null;
    }

    start_common = new Date($('#start_time_0').value).getTime();
    end_common = new Date($('#end_time_0').value).getTime();
    start_opiton = new Date(control.get('start_time').value).getTime();
    end_option = new Date(control.get('end_time').value).getTime();

    if ((start_common - start_opiton) < 3600000 && (end_common - end_option) < 3600000) {
        return { invalidDateCommon: true };
    }

    if ((start_common - start_opiton) < 3600000) {
        return { invalidDateStart: true };
    }

    if ((end_common - end_option) < 3600000) {
        return { invalidDateEnd: true };
    }

    return null;
}
