import {BaseComponent} from '../../app/base.component';
import {FormArray, FormGroup, NgForm, Validators} from '@angular/forms';

declare var $: any;

export class CampaignBaseComponent extends BaseComponent {
    protected data;
    protected campaign_mail: FormArray;
    protected campaign_option: FormArray;
    protected origin_store = [];
    protected destination_store = [];
    protected list_photo;
    protected list_template_mail;
    protected current_option = 0;
    protected json_campaign_option;
    protected json_photo_option;
    protected array_photo_option = [];
    protected empty_array = [];
    protected current_weekday_benefits = [];
    protected current_holiday_benefits = [];
    protected campaign_content_value = [];
    protected created_option = [];
    protected saved_option = [];
    protected created_status = 1;
    protected not_create_status = 0;
    protected timeDefault = [30, 60, 90, 120, 150, 180, 480];
    public dateTimeRange = [[]];
    public currentdate: Date[] = [new Date(), new Date()];
    public show_button = true;
    public radio_type = 0;
    protected isResetPhoto = false;
    // save data
    public old_selected_photo = null;
    protected flag_photo = 0;

    protected initform() {
        this.formdata = this.fb.group({
            name: ['', Validators.required],
            web_name: ['', Validators.required],
            store: this.fb.array([]),
            time: ['', Validators.required],
            photo_id: ['', Validators.required],
            code: this.json.create_id(),
            comment: [''],
            is_display_calendar: [''],
            option: this.fb.array([]),
            mail: this.fb.array([]),
            last_update_by: ['']
        });
    }

    protected assignform(data) {

        let counter = 0;

        Object.keys(this.formdata.controls).forEach(key => {
            this.formdata.get(key).patchValue(data[key]);
        });

        this.radio_type = data['is_display_calendar'];
        // assign campaign store
        data.store.forEach((Object) => {
            this.mvoption(Object['id']);
        });

        // assign campaign option
        data.option.forEach((value) => {
            this.currentdate = [];
            this.currentdate[0] = new Date(value.start_time);
            this.currentdate[1] = new Date(value.end_time);
            if (counter === 0) {
                this.dateTimeRange[0] = this.currentdate;
            } else {
                this.dateTimeRange.push(this.currentdate);
            }

            this.campaign_option = this.formdata.get('option') as FormArray;
            this.campaign_option.push(this.getOption(value));
            this.campaign_content_value.push(value['content']);
            counter++;
        });

        // assign campaign mail
        data.mail.forEach((value) => {
            this.campaign_mail = this.formdata.get('mail') as FormArray;
            this.campaign_mail.push(this.getMail(value));
        });
    }

    protected update_current_status() {

        let counter;

        if (this.campaign_option) {
            for (counter = 0; counter < this.campaign_option.length; counter++) {
                this.created_option[counter] = this.created_status;
                this.saved_option[counter] = this.created_status;
                this.current_weekday_benefits[counter] = this.campaign_option.value[counter]['weekday_benefits'];
                this.current_holiday_benefits[counter] = this.campaign_option.value[counter]['holiday_benefits'];
            }
        }
    }

    protected preSubmit(data) {
        let counter;

        data['last_update_by'] = this.getInfoLoginUser().id;
        data['store'] = this.destination_store;

        if (data.option.length > 0) {
            for (counter = 0; counter < data.option.length; counter++) {
                data.option[counter].campaign_id = this.id ? this.id : '';
                data.option[counter].is_deleted = false;
                if (data.option[counter].start_time !== '') {
                    data.option[counter].start_time = this.getdate(this.dateTimeRange[counter][0]);
                }
                if (data.option[counter].end_time !== '') {
                    data.option[counter].end_time = this.getdate(this.dateTimeRange[counter][1]);
                }

                if (data.option[counter].content === '') {
                    data.option[counter].content = this.update_content();
                }
            }
        }
    }

    protected handlesubmit(res) {
        super.getResponseMessageSubmit(res);
        if (this.success) {
            super.checkSubmit('campaign');
        }
    }

    protected createMail(): FormGroup {
        return this.fb.group({
            id: [''],
            type: ['1'],
            day: ['1'],
            action: ['1'],
            template: ['1']
        });
    }

    protected getMail(data?): FormGroup {
        return this.fb.group({
            id: [data.id],
            type: [data.type],
            day: [data.day],
            action: [data.action],
            template: [data.template]
        });
    }

    protected addMail(): void {
        this.campaign_mail = this.formdata.get('mail') as FormArray;
        this.campaign_mail.push(this.createMail());
    }

    protected removeMail(id): void {
        this.campaign_mail.removeAt(id);
    }

    protected createOption(flag: boolean): FormGroup {
        const currentDate = new Date(Date.now());
        this.dateTimeRange.push([currentDate, currentDate]);
        return this.fb.group({
            id: [''],
            type: flag ? 1 : 2,
            start_time: currentDate,
            end_time: currentDate,
            weekday_fee: [''],
            holiday_fee: [''],
            memo: [''],
            content: [''],
            campaign_id: this.id ? this.id : ''
        });
    }

    protected getOption(data?): FormGroup {
        return this.fb.group({
            id: [data.id],
            type: data.type,
            start_time: new Date(data.start_time),
            end_time: new Date(data.end_time),
            weekday_fee: data.weekday_fee,
            holiday_fee: data.holiday_fee,
            memo: data.memo ? data.memo : '',
            weekday_benefits: data.weekday_benefits,
            holiday_benefits: data.holiday_benefits,
            content: data.content,
            campaign_id: this.id ? this.id : ''
        });
    }

    protected addOption(flag: boolean): void {
        this.campaign_option = this.formdata.get('option') as FormArray;
        this.campaign_option.push(this.createOption(flag));
        this.array_photo_option.push(this.empty_array);
    }

    protected removeOption(id): void {
        this.campaign_option.removeAt(id);
        this.dateTimeRange.splice(id, 1);
    }

    protected elementRemove(arr, id) {
        return arr.filter(function (obj) {
            return obj['id'].toString() !== id.toString();
        });
    }

    protected getElementRemove(arr, id) {
        if (!arr) {
            return null;
        }
        return arr.filter(function (obj) {
            return obj['id'].toString() === id.toString();
        });
    }

    protected mvoption(id) {
        // use "filter" to avoid deleted all array ( not use "slice" here)
        const element = this.elementRemove(this.origin_store, id);
        // use "filter" to avoid deleted all array ( not use "slice" here)
        const array = this.getElementRemove(this.origin_store, id);
        this.origin_store = [];
        this.origin_store = element;
        // use "concat" to avoid duplication array copy (not use "push" here)
        const concat = this.destination_store.concat(array);
        this.destination_store = [];
        this.destination_store = concat;
    }

    protected svoption(id) {
        // use "filter" to avoid deleted all array ( not use "slice" here)
        const element = this.elementRemove(this.destination_store, id);
        // use "filter" to avoid deleted all array ( not use "slice" here)
        const array = this.getElementRemove(this.destination_store, id);
        this.destination_store = [];
        this.destination_store = element;
        // use "concat" to avoid duplication array copy (not use "push" here)
        const concat = this.origin_store.concat(array);
        this.origin_store = [];
        this.origin_store = concat;
    }

    protected moveItems(el, origin, dist) {
        const list_item = [];
        $(el.target).parent().parent().find(origin).find(':selected').each(function (i, obj) {
            list_item.push(obj.value);
        });
        if (origin === '.s1') {
            list_item.forEach((Object) => {
                this.mvoption(Object);
            });
        } else {
            list_item.forEach((Object) => {
                this.svoption(Object);
            });
        }
    }

    protected get_current_photo(): string {
        return $('#select_photo').find(':selected').val();
    }

    protected get_id(): string {
        const select_photo = this.get_current_photo();
        let counter;
        let id;
        if (select_photo !== 'undefined') {
            for (counter = 0; counter < this.list_photo.length; counter++) {
                if (this.list_photo[counter]['id'].toString() === select_photo) {
                    id = counter;
                }
            }
        } else {
            return null;
        }
        return id;
    }

    parse_data() {

        let i, k;
        this.json_campaign_option = [];
        this.json_campaign_option = this.json.decode(this.campaign_content_value[this.current_option]);
        if (!this.json_campaign_option) {
            return;
        }
        if (this.json_photo_option.length === 0) {
            return;
        }

        if ( (this.old_selected_photo !== null) && (this.old_selected_photo != this.get_current_photo())) {
            return;
        }

        if (this.flag_photo === 0) {
            return;
        }

        for (i = 0; i < Object.keys(this.json_campaign_option).length; i++) {
            for (k = 0; k < Object.keys(this.json_campaign_option[i]['select']).length; k++) {
                this.json_photo_option[i]['select'][k]['holiday_price']
                    = this.json_campaign_option[i]['select'][k]['holiday_price'];
                this.json_photo_option[i]['select'][k]['weekday_price']
                    = this.json_campaign_option[i]['select'][k]['weekday_price'];
            }
        }
    }

    protected update_content(): string {
        const id = this.get_id();
        let content: string;
        this.json_photo_option = [];
        if ((this.list_photo.length > 0) && (typeof id !== 'undefined')) {
            content = this.list_photo[id].option[0]['content'];
        }

        return content;
    }

    get_photo_json() {}

    protected openModal(i) {
        this.current_option = i;
        // check create new option
        if (this.created_option.length <= this.current_option) {
            this.array_photo_option.push(this.empty_array);
            this.current_weekday_benefits[this.current_option] = null;
            this.current_holiday_benefits[this.current_option] = null;
        }
        //
        this.get_photo_json();
        this.array_photo_option[this.current_option] = this.json.json2array(this.json_photo_option);
    }

    protected onsubmitOption(form: NgForm) {
        // save old select photo
        if (this.flag_photo === 0) {
            this.old_selected_photo = this.get_current_photo();
            this.flag_photo = 1;
        }

        let i, j;
        for (i = 0; i < this.array_photo_option[this.current_option].length; i++) {
            for (j = 0; j < this.array_photo_option[this.current_option][i].select.length; j++) {
                this.json_photo_option[i]['select'][j]['holiday_price']
                    = form.value[i + '_' + j + '_holiday_price'];
                this.array_photo_option[this.current_option][i].select[j].holiday_price = form.value[i + '_' + j + '_holiday_price'];
                this.json_photo_option[i]['select'][j]['weekday_price']
                    = form.value[i + '_' + j + '_weekday_price'];
                this.array_photo_option[this.current_option][i].select[j].weekday_price = form.value[i + '_' + j + '_weekday_price'];
            }
        }
        this.campaign_option.value[this.current_option]['weekday_benefits'] = form.value['weekday_benefits'];
        this.campaign_option.value[this.current_option]['holiday_benefits'] = form.value['holiday_benefits'];
        this.campaign_option.value[this.current_option]['content'] = this.json.encode(this.json_photo_option);
        this.current_weekday_benefits[this.current_option] = form.value['weekday_benefits'];
        this.current_holiday_benefits[this.current_option] = form.value['holiday_benefits'];
        this.campaign_content_value[this.current_option] = this.json.encode(this.json_photo_option);
        this.created_option[this.current_option] = 1;
        this.closemodal();
    }

    protected reset() {
        this.flag_photo = 0;
        const currentDate = new Date(Date.now());
        this.array_photo_option = [];
        this.json_photo_option = [];
        this.json_campaign_option = [];
        this.current_weekday_benefits = [];
        this.current_holiday_benefits = [];
        this.created_option = [];
        this.created_option[0] = 0;
        this.saved_option = [];
        this.isResetPhoto = true;
        this.dateTimeRange[0] = [currentDate, currentDate];
        const control = <FormArray>this.formdata.controls['option'];
        for (let i = control.length - 1; i >= 0; i--) {
            control.removeAt(i);
        }
        this.addOption(true);
    }

    protected validate(): boolean {
        if (this.formdata.valid) {
            if (this.destination_store.length !== 0) {
                $('#submit_campaign').prop('disabled', false);
                return true;
            }
        }
        $('#submit_campaign').prop('disabled', true);
        return false;
    }

    protected closemodal() {
        $('#myModal').modal('hide');
    }

    protected checkDate() {
        $(document).ready(function () {
            let i = 0;
            $('.time-value').each(function() {
                const time = new Date($(this).val()).getDate();
                if (isNaN(time)) {
                    i++;
                    $(this).css('border-left', '3px solid red');
                } else {
                    $(this).css('border-left', '1px solid #ced4da');
                }
            });
            if (i > 0) {
                $('#submit_campaign').prop('disabled', true);
            } else {
                $('#submit_campaign').prop('disabled', false);
            }
        });
    }
}
