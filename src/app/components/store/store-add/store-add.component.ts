import {Component, OnInit} from '@angular/core';
import {StoreBaseComponent} from '../store-base.component';
import {Validators} from '@angular/forms';
import {HttpHeaders} from '@angular/common/http';
import {CompareTime} from '../../../validators/compare.time.weekend';


const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/json'
    })
};

@Component({
    selector: 'app-store-add',
    templateUrl: '../store-base.component.html',
    styleUrls: ['./store-add.component.css']
})
export class StoreAddComponent extends StoreBaseComponent implements OnInit {

    ngOnInit() {
        this.getData();
        this.formdata = this.fb.group({
            name: ['', Validators.required],
            phone: [''],
            code: this.json.create_id(),
            manager_id: [0],
            weekday_start_time: ['', Validators.required],
            weekday_end_time: ['', Validators.required],
            holiday_start_time: ['', Validators.required],
            holiday_end_time: ['', Validators.required],
            day_off_monday: [''],
            day_off_tuesday: [''],
            day_off_wednesday: [''],
            day_off_thursday: [''],
            day_off_friday: [''],
            day_off_saturday: [''],
            day_off_sunday: [''],
            fixed_days_off: [''],
            fixed_days_on: [''],
            comment: [''],
            sign_email: [''],
            lanes: this.fb.array([]),
            last_update_by: ['']
        }, { validator: [CompareTime] });

        // assign time
        this.wTimeRangeStart = super.getbasetimeStart();
        this.wTimeRangeEnd = super.getbasetimeEnd();
        this.hTimeRangeStart = super.getbasetimeStart();
        this.hTimeRangeEnd = super.getbasetimeEnd();
    }

    onSubmit(data) {
        this.preSubmit(data);
        this.post('./api/store', data).subscribe(res => {
            this.handlesubmit(res);
        });
    }
}
