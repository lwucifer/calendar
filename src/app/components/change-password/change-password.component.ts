import {Component} from '@angular/core';
import {BaseComponent} from '../../app/base.component';
import {Validators} from '@angular/forms';
import {HttpHeaders} from '@angular/common/http';



@Component({
    selector: 'app-change-password',
    templateUrl: './change-password.component.html',
    styleUrls: ['./change-password.component.css']
})
export class ChangePasswordComponent extends BaseComponent {

    init() {
        this.formdata = this.fb.group({
            current_password: ['', Validators.required],
            password: ['', Validators.required],
            password_confirmation: ['', Validators.required]
        });
    }

    onSubmit(data) {
        this.post('./api/change-password', JSON.stringify(data)).subscribe(res => {
            this.handleSubmit(res);
        });
    }

    handleSubmit(res) {
        super.getResponseMessageSubmit(res);
        if ( this.success ) {
            const message = this.translate.instant('change_password.success');
            super.checkSubmit('/', message);
        }
    }
}
