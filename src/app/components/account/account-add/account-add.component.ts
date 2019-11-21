import {Component} from '@angular/core';
import {Validators} from '@angular/forms';
import {AccountBaseComponent} from '../account-base.component';

@Component({
    selector: 'app-account-add',
    templateUrl: './../account-base.component.html',
    styleUrls: ['./account-add.component.css']
})

export class AccountAddComponent extends AccountBaseComponent {
    getAccount() {
        this.role_id  = '';
        this.store_id = !this.isAdmin() ? this.getInfoLoginUser('store_id') : '';

        this.formdata = this.fb.group({
            first_name: ['', Validators.required],
            last_name: ['', Validators.required],
            kana_first_name: ['', Validators.required],
            kana_last_name: ['', Validators.required],
            email: ['', Validators.required],
            phone: ['', Validators.required],
            zip_code: [''],
            address1: [''],
            address2: [''],
            comment: [''],
            username: ['', Validators.required],
            password: ['', Validators.required],
            co_password: ['', Validators.required],
            role_id: [this.role_id, Validators.required],
            store_id: [{value: this.store_id, disabled: this.disabledStore()}, Validators.required]
        });

        this.loaded = true;
    }

    onSubmit(data) {
        data = this.checkDataBeforeSubmit(data);

        this.post('./api/user', data).subscribe((response) => {
            this.handleSubmit(response);
        });
    }

}
