import {Component} from '@angular/core';
import {HttpHeaders} from '@angular/common/http';
import {Validators} from '@angular/forms';
import {PhotoBaseComponent} from '../photo-base.component';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-photo-edit',
    templateUrl: './../photo-base.component.html',
    styleUrls: ['./photo-edit.component.css']
})
export class PhotoEditComponent extends PhotoBaseComponent{
    init() {
        this.getCash();
        this.formdata = this.fb.group({
            name: ['七五三撮影', Validators.required],
            comment: [''],
            cash_id: [''],
            option: this.fb.array(
                []
            )
        });
        this.id = this.route.snapshot.paramMap.get('id');
        this.http.get('./api/photo/' + this.id).subscribe(data => {
            if (!this.checkRequestSuccess(data)) {
                // redirect 404;
                this.router.navigate(['/404'], { skipLocationChange: true });
                return;
            }
            this.results = data['response'].data;
            this.patchForm();
            this.loaded = true;
        });

        this.initDrag();
    }

    patchForm() {
        this.formdata.patchValue({
            name: this.results.name,
            comment: this.results.comment,
            cash_id: this.results.cash_id[0].id
        });
        this.setOptions();
    }

    onSubmit(data) {
        this.http.patch('./api/photo/'  + this.id, JSON.stringify(data), ParseHeaders).subscribe(res => {
            this.handleSubmit(res);
        });
    }
}
