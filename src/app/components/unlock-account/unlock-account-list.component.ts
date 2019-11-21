import {Component} from '@angular/core';
import {HttpHeaders} from '@angular/common/http';
import {BaseComponent} from '../../app/base.component';

const ParseHeadersJson = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-unlock-account-list',
    templateUrl: './unlock-account-list.component.html',
    styleUrls: ['./unlock-account-list.component.css']
})

export class UnlockAccountListComponent extends BaseComponent {
    roles;
    f_name;
    f_user_id;
    runAction = false;

    init() {
        this.get('./api/unlock?page=' + this.page).subscribe(response => {this.handleData(response);});
        this.get('./api/role').subscribe(response => this.roles = this.parseHttpRes(response));
    }

    handleSubmit(res) {
        if (!this.isAdmin()) {
            throw new Error('401 Un-authenticate');
        }

        this.getResponseMessageSubmit(res);

        if ( this.success ) {
            super.checkSubmit('unlock');
            this.init();
        }
    }

    unlockAccount(id) {
        this.runAction = true;

        this.patch('./api/unlock/' + id, JSON.stringify({}), ParseHeadersJson)
            .subscribe(res => {
                this.handleSubmit(res);
            });
    }

}
