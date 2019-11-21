import {StorageComponent} from './storage.component';

export class ManagerComponent {
    protected storageKey = {
        USER_INFO: 'USER_INFO',
        CHECK_REDIRECT_LOGIN: 'CHECK_REDIRECT_LOGIN'
    };

    protected roleName = {
        manager: 'manager',
        user: 'user',
        admin: 'admin',
        customer: 'customer'
    };

    protected roleType = {
        manager: 3,
        user: 2,
        admin: 1,
        customer: 4
    };

    isAuth(role) {
        if (this.getInfoLoginUser() !== null) {
            StorageComponent.setItemInStorage(this.storageKey.CHECK_REDIRECT_LOGIN, true);

            return this.getInfoLoginUser().role.name === role;
        }
    }

    getInfoLoginUser(key = '') {
        const userInfo = StorageComponent.getItemFromStorage(this.storageKey.USER_INFO);
        return key ? userInfo[key] : userInfo;
    }

    isManager() {
        return this.isAuth(this.roleName.manager);
    }

    isUser() {
        return this.isAuth(this.roleName.user);
    }

    isCustomer() {
        return this.isAuth(this.roleName.customer);
    }

    isAdmin() {
        return this.isAuth(this.roleName.admin);
    }
}
