import {Component} from '@angular/core';
import {BaseComponent} from '../../app/base.component';

@Component({
    selector: 'app-account',
})

export class AccountBaseComponent extends BaseComponent {
    roles;
    stores;
    data;
    store_id = null;
    role_id = null;
    profile = false;

    init() {
        this.getData();
        this.getAccount();
    }

    getData() {
        this.get('./api/store').subscribe(response => {
            this.stores = this.parseHttpRes(response)['list'];
        });
        this.get('./api/role').subscribe(response => {
            this.roles = this.customRoleData(this.parseHttpRes(response));
        });
    }

    getAccount() {}

    handleSubmit(res) {
        if (this.isCustomer()) {
            throw new Error('401 Un-authenticate');
        }

        this.getResponseMessageSubmit(res);

        if ( this.success ) {
            super.checkSubmit('account');
        }
    }

    customRoleData(roles) {
        if (this.isAdmin() && !this.profile) {
            return roles.filter(e => e['name'] !== this.roleName.admin);
        }

        if (this.isManager() && !this.profile) {
            return roles.filter(e => e['name'] !== this.roleName.admin && e['name'] !== this.roleName.manager);
        }

        return roles;
    }

    checkDataBeforeSubmit(data) {
        if (this.isManager()) {
            data.role_id  = this.roleType.user;
            data.store_id = this.getInfoLoginUser('store_id');
        }

        return data;
    }

    hasValidateStore() {
        return this.store_id !== null && this.role_id !== this.roleType.admin;
    }

    hasValidateRole() {
        return this.role_id !== null && this.role_id !== this.roleType.admin;
    }

    disabledRole() {
        return !this.isAdmin() || this.profile;
    }

    disabledStore() {
        return !this.isAdmin() || this.profile;
    }
}
