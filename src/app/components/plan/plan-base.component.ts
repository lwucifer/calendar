import {BaseComponent} from '../../app/base.component';
import {FormArray, FormGroup, Validators} from '@angular/forms';

declare var $: any;

export class PlanBaseComponent extends BaseComponent {
    protected success: boolean;
    protected results;
    protected date_type;
    protected message = [];
    protected formdata: FormGroup;
    protected all_option;
    protected custom_info;
    protected campaigns;
    protected stores;
    protected loaded = false;
    protected plan_start_time: Date;
    protected plan_end_time: Date;
    protected showMessageRequire = false;
    protected schedules;
    protected disable_choose_time = false;
    protected disable_choosen_status = false;
    protected status_list = [
        // {id: 1, name: '新しい予約'},
        {id: 2, name: '予約認識中'},
        {id: 3, name: 'キャンセル'},
        {id: 4, name: '予約完了'}
    ];

    protected status_list_enable = [
        // {id: 1, name: '新しい予約'},
        {id: 2, name: '予約認識中'},
        {id: 3, name: 'キャンセル'}
    ];

    initform() {
        this.formdata = this.fb.group({
            store_id: ['', Validators.required],
            campaign_id: ['', Validators.required],
            date: ['', Validators.required],
            schedule_time: [''],
            start_time: ['', Validators.required],
            end_time: ['', Validators.required],
            status: ['', Validators.required],
            comment: [''],
            user_name:['', Validators.required],
            user_phone:['', Validators.required],
            user_email:[''],
        });
    }

    assignform(data) {
        Object.keys(this.formdata.controls).forEach(key => {
            switch (key) {
                case 'date':
                    this.formdata.get(key).patchValue(new Date(data[key]));
                    break;
                case 'start_time':
                case 'end_time':
                    this.formdata.get(key).patchValue(new Date(data.date + ' ' + data[key]));
                    break;
                case 'campaign_id':
                    this.formdata.get(key).patchValue(data.campaign.id);
                    break;
                case 'store_id':
                    this.formdata.get(key).patchValue(data.store.id);
                    break;
                case 'status':
                    this.formdata.get(key).patchValue(data.status);
                    break;
                case 'schedule_time':
                    this.schedules.forEach( time => {
                        if ( data.start_time === time['time_start'] && data.end_time === time['time_end']) {
                            this.formdata.get(key).patchValue(time.id);
                        }
                    });
                    break;
                default:
                    this.formdata.get(key).patchValue(data[key]);
                    break;
            }
        });

        this.plan_start_time = super.showTime(this.results.start_time);
        this.plan_end_time = super.showTime(this.results.end_time);
        const today = (new Date(data['date'])).getDay();
        this.date_type = this.results.date_type;
    }

    changeDate() {
        const date = new Date($('#date').val());
        this.date_type = (date.getDay() === 6) || (date.getDay() === 0);
    }

    preSubmit(data) {
        this.showMessageRequire = false;
        for (let i = 0; i < this.all_option.length; i++) {
            let flag_check = false;
            for (let j = 0; j < this.all_option[i]['select'].length; j++) {
                const name = 'option_' + i + '_' + j;
                const checked = $('input[name="' + name + '"]:checked').length;
                const name_radio = 'option_' + i;
                const radio = $('input[name="' + name_radio + '"]:checked').val() == j;
                if (this.all_option[i]['type'] === '1') {
                    this.all_option[i]['select'][j]['status'] = checked ? 1 : 0;
                } else {
                    this.all_option[i]['select'][j]['status'] = radio ? 1 : 0;
                }
                if (checked || radio) {
                    flag_check = true;
                }
            }
            if (this.all_option[i]['require']) {
                if (flag_check) {
                    this.showMessageRequire = false;
                } else {
                    this.showMessageRequire = true;
                }
            }
        }
        data['content'] = JSON.stringify(this.all_option);
        data['last_update_by'] = this.getInfoLoginUser().id;
        // change date format
        data.status = parseInt(data.status, 10);
        data.date = this.formatDate(data.date);
        data.start_time = this.schedules[this.get_current_time()]['time_start'];
        data.end_time = this.schedules[this.get_current_time()]['time_end'];
        data['user_id'] = this.getInfoLoginUser().id;
    }

    handlesubmit(data) {
        super.getResponseMessageSubmit(data);
        if (this.success) {
            super.checkSubmit('plan');
        }
    }

    reset() {
        this.all_option = null;
        const control = <FormArray>this.formdata.controls['option'];
        for (let i = control.length - 1; i >= 0; i--) {
            control.removeAt(i);
        }
    }

    validate(): boolean {
        return this.formdata.valid;
    }

    get_current_time(): string {
        return $('#schedule_time').find(':selected').val();
    }

    convertTimeFormat(data) {
        return data.substring(0, data.length - 3);
    }

}
