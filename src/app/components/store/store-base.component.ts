import { BaseComponent } from '../../app/base.component';
import { FormGroup, FormArray } from '@angular/forms';
import {CompareTime} from '../../validators/compare.time.weekend';

declare var $: any;

export class StoreBaseComponent extends BaseComponent {
    protected manager;
    protected photo;
    protected lanes: FormArray;
    protected origin_photo = [[]];
    protected destination_photo = [[]];
    protected empty_array = [];
    protected wTimeRangeStart;
    protected wTimeRangeEnd;
    protected hTimeRangeStart;
    protected hTimeRangeEnd;
    public wlaneTimeRangeStart = [];
    public wlaneTimeRangeEnd = [];
    public hlaneTimeRangeStart = [];
    public hlaneTimeRangeEnd = [];

    getData() {
        this.get('./api/photo/list').subscribe(data => {
            this.photo = data;
            this.origin_photo[0] = this.photo.response.data;
            this.loaded = true;
        });
    }

    createItem(): FormGroup {
        this.origin_photo.push(this.photo.response.data);
        this.destination_photo.push(this.empty_array);

        this.wlaneTimeRangeStart.push(super.getbasetimeStart());
        this.wlaneTimeRangeEnd.push(super.getbasetimeEnd());
        this.hlaneTimeRangeStart.push(super.getbasetimeStart());
        this.hlaneTimeRangeEnd.push(super.getbasetimeEnd());

        return this.fb.group({
            id: [''],
            name: [''],
            order: [''],
            number: [''],
            weekday_start_time: [''],
            weekday_end_time: [''],
            holiday_start_time: [''],
            holiday_end_time: [''],
            visit_time: [''],
            store_id: this.id,
            selected_photo: ['']
        }, { validator: [CompareTime]});
    }

    addItem(): void {
        this.lanes = this.formdata.get('lanes') as FormArray;
        this.lanes.push(this.createItem());
    }

    removeItem(id): void {
        this.lanes.removeAt(id);
        this.origin_photo[id] = this.photo.response.data;
        this.destination_photo[id] = [];
    }

    arrayRemove(arr, id) {
        return arr.filter(function (obj) {
            return obj['id'].toString() !== id.toString();
        });
    }

    getarrayRemove(arr, id) {
        return arr.filter(function (obj) {
            return obj['id'].toString() === id.toString();
        });
    }

    mvoption(id, element) {
        const array = this.arrayRemove(this.origin_photo[element], id);
        const getarray = this.getarrayRemove(this.origin_photo[element], id);
        this.origin_photo[element] = [];
        this.origin_photo[element] = array;
        const concat = this.destination_photo[element].concat(getarray);
        this.destination_photo[element] = [];
        this.destination_photo[element] = concat;
    }

    svoption(id, element) {
        const array = this.arrayRemove(this.destination_photo[element], id);
        const getarray = this.getarrayRemove(this.destination_photo[element], id);
        this.destination_photo[element] = [];
        this.destination_photo[element] = array;
        const concat = this.origin_photo[element].concat(getarray);
        this.origin_photo[element] = [];
        this.origin_photo[element] = concat;
    }

    moveItems(el, origin) {
        const list_item = [];
        let element;
        $(el.target).parent().parent().find(origin).find(':selected').each(function (i, obj) {
            list_item.push(obj.value);
        });
        $(el.target).parent().parent().find('.lane_id').each(function (i, obj) {
            element = obj.value;
        });
        if (origin === '.s1') {
            list_item.forEach((Object) => {
                this.mvoption(Object, element);
            });
        } else {
            list_item.forEach((Object) => {
                this.svoption(Object, element);
            });
        }
    }

    preSubmit(data) {
        data['weekday_start_time'] = this.getTime(this.wTimeRangeStart);
        data['weekday_end_time'] = this.getTime(this.wTimeRangeEnd);
        data['holiday_start_time'] = this.getTime(this.hTimeRangeStart);
        data['holiday_end_time'] = this.getTime(this.hTimeRangeEnd);
        data['lanes'].forEach((Object, index) => {
            Object['selected_photo'] = this.destination_photo[index];
            Object['weekday_start_time'] = this.getTime(this.wlaneTimeRangeStart[index]);
            Object['weekday_end_time'] = this.getTime(this.wlaneTimeRangeEnd[index]);
            Object['holiday_start_time'] = this.getTime(this.hlaneTimeRangeStart[index]);
            Object['holiday_end_time'] = this.getTime(this.hlaneTimeRangeEnd[index]);
        });
        data['last_update_by'] = this.getInfoLoginUser().id;
    }

    protected handlesubmit(res) {
        super.getResponseMessageSubmit(res);
        if ( this.success ) {
            super.checkSubmit('store');
        }
    }
}
