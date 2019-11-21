import {Component} from '@angular/core';
import {Validators} from '@angular/forms';
import {AccountBaseComponent} from '../account-base.component';
import {HttpHeaders} from '@angular/common/http';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/json'
    })
};

@Component({
    selector: 'app-account-edit',
    templateUrl: './../account-base.component.html',
    styleUrls: ['./account-edit.component.css']
})

export class AccountEditComponent extends AccountBaseComponent {
    getAccount() {
        this.id = this.route.snapshot.paramMap.get('id');

        this.get('./api/user/' + this.id).subscribe(data => {

            if (!this.checkRequestSuccess(data)) {
                // redirect 404;
                this.router.navigate(['/404'], { skipLocationChange: true });
                return;
            }

            this.results  = this.parseHttpRes(data);
            this.role_id  = this.isManager() ? this.roleType.user : this.results.role.id;
            this.store_id = this.results.store ? this.results.store.id : null;

            this.formdata = this.fb.group({
                first_name: [this.results.first_name, Validators.required],
                last_name: [this.results.last_name, Validators.required],
                kana_first_name: [this.results.kana_first_name, Validators.required],
                kana_last_name: [this.results.kana_last_name, Validators.required],
                email: [this.results.email, Validators.required],
                phone: [this.results.phone, Validators.required],
                zip_code: [this.results.zip_code],
                address1: [this.results.address1],
                address2: [this.results.address2],
                comment: [this.results.comment],
                username: [this.results.username],
                role_id: [
                    {value: this.role_id, disabled: this.disabledRole()},
                    Validators.required
                ],
                store_id: [
                    {value: this.store_id, disabled: this.disabledStore()},
                    this.hasValidateStore() ? Validators.required : false
                ]
            });

            this.loaded = true;
        });
    }

    onSubmit(data) {
        data = this.checkDataBeforeSubmit(data);

        this.patch('./api/user/' + this.id, data, ParseHeaders).subscribe(res => {
            this.handleSubmit(res);
        });
    }
}
