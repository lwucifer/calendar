import {Component, OnInit} from '@angular/core';
import {StoreBaseComponent} from '../store-base.component';
import {Validators, FormArray, FormGroup} from '@angular/forms';
import {CompareTime} from '../../../validators/compare.time.weekend';

@Component({
    selector: 'app-store-edit',
    templateUrl: '../store-base.component.html',
    styleUrls: ['./store-edit.component.css']
})

export class StoreEditComponent extends StoreBaseComponent {
    counter = 0;

    init() {
        this.id = this.route.snapshot.paramMap.get('id');
        this.getStore();
    }

    getStore() {
        this.get('./api/store/' + this.id).subscribe(data => {

            if (!this.checkRequestSuccess(data)) {
                // redirect 404;
                this.router.navigate(['/404'], { skipLocationChange: true });
                return;
            }
            this.results = data;
            this.results = this.results.response.data;
            this.formdata = this.fb.group({
                name: [this.results.name, Validators.required],
                phone: [this.results.phone],
                code: [this.results.code],
                manager_id: [0],
                weekday_start_time: [super.showTime(this.results.weekday_start_time), Validators.required],
                weekday_end_time: [super.showTime(this.results.weekday_end_time), Validators.required],
                holiday_start_time: [super.showTime(this.results.holiday_start_time), Validators.required],
                holiday_end_time: [super.showTime(this.results.holiday_end_time), Validators.required],
                day_off_monday: [this.results.day_off_monday],
                day_off_tuesday: [this.results.day_off_tuesday],
                day_off_wednesday: [this.results.day_off_wednesday],
                day_off_thursday: [this.results.day_off_thursday],
                day_off_friday: [this.results.day_off_friday],
                day_off_saturday: [this.results.day_off_saturday],
                day_off_sunday: [this.results.day_off_sunday],
                fixed_days_off: [this.results.fixed_days_off],
                fixed_days_on: [this.results.fixed_days_on],
                comment: [this.results.comment],
                sign_email: [this.results.sign_email],
                lanes: this.fb.array([])
            }, { validator: [CompareTime] });

            // assign  value
            this.results.lane.forEach((value) => {
                this.lanes = this.formdata.get('lanes') as FormArray;
                this.lanes.push(this.getItem(value));
            });

            this.wTimeRangeStart = super.showTime(this.results.weekday_start_time);
            this.wTimeRangeEnd = super.showTime(this.results.weekday_end_time);
            this.hTimeRangeStart = super.showTime(this.results.holiday_start_time);
            this.hTimeRangeEnd = super.showTime(this.results.holiday_end_time);

            this.http.get('./api/photo/list').subscribe(photo => {
                this.photo = photo;
                this.loaded = true;
                this.origin_photo[0] = this.photo.response.data;

                // assign list photo
                if (this.results.lane) {
                    this.results.lane.forEach((Object) => {
                        this.origin_photo.push(this.photo.response.data);
                        this.destination_photo.push(this.empty_array);
                        if (Object['photo']) {
                            Object['photo'].forEach((obj) => {
                                this.mvoption(obj['id'], this.counter);
                            });
                        }
                        this.counter++;
                    });
                }
            });
        });
    }

    onSubmit(data) {
        this.preSubmit(data);

        this.patch('./api/store/' + this.id, data).subscribe(res => {
            this.handlesubmit(res);
        });
    }

    getItem(data?): FormGroup {

        this.wlaneTimeRangeStart.push(super.showTime(data.weekday_start_time));
        this.wlaneTimeRangeEnd.push(super.showTime(data.weekday_end_time));
        this.hlaneTimeRangeStart.push(super.showTime( data.holiday_start_time));
        this.hlaneTimeRangeEnd.push(super.showTime(data.holiday_end_time));
        return this.fb.group({
            id: [data.id],
            name: [data.name],
            order: [data.order],
            number: [data.number],
            weekday_start_time: super.showTime(data.weekday_start_time),
            weekday_end_time:   super.showTime(data.weekday_end_time),
            holiday_start_time: super.showTime( data.holiday_start_time),
            holiday_end_time:   super.showTime(data.holiday_end_time),
            visit_time: [data.visit_time],
            store_id: this.id,
            selected_photo: ['']
        });
    }

}
