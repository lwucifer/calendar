import {Component} from '@angular/core';
import {Validators} from '@angular/forms';
import {AccountBaseComponent} from '../account-base.component';

@Component({
    selector: 'app-account-profile',
    templateUrl: '../account-base.component.html',
    styleUrls: ['./account-profile.component.css']
})
export class AccountProfileComponent extends AccountBaseComponent {
    profile = true;

    getAccount() {
        this.results  = this.getInfoLoginUser();
        this.id       = this.getInfoLoginUser('id') || '';
        this.role_id  = this.getInfoLoginUser('role_id');
        this.store_id = this.getInfoLoginUser('store_id');

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
            role_id: [{value: this.role_id, disabled: this.disabledRole()}, Validators.required],
            store_id: [{value: this.store_id, disabled: this.disabledStore()}, Validators.required]
        });

        this.loaded = true;
    }

    onSubmit(data) {
        data['id']       = this.id;
        data['role_id']  = this.getInfoLoginUser('role_id');
        data['store_id'] = this.getInfoLoginUser('store_id');

        this.patch('./api/user/' + this.id, data).subscribe(res => {
            this.handleSubmit(res);
        });
    }
}
