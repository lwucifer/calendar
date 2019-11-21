import {Component} from '@angular/core';
import {NgForm, Validators} from '@angular/forms';
import {AccountBaseComponent} from '../account-base.component';
declare var jquery: any;
declare var $: any;

@Component({
    selector: 'app-account',
    templateUrl: './account.component.html',
    styleUrls: ['./account.component.css']
})

export class AccountComponent extends AccountBaseComponent {
    fnumber;
    f_name;
    f_user_id;
    attrImport = 'user';

    init() {
        this.getData();
        this.refreshData();
    }

    refreshData() {
        this.isearch ? this.toSearch(this.searchUrl)
                     : this.get('./api/user?page=' + this.page).subscribe(response => this.handleData(response));
    }

    openModal(id) {
        this.fnumber = this.results[id].f_number;
        this.f_name = this.results[id].f_number[0].name;
        this.f_user_id = this.results[id].id;
        this.formdata = this.fb.group({
            name: ['', Validators.required],
            memo: ['', Validators.required]
        });
    }

    onSubmit(data) {
        data['user_id'] = this.f_user_id;
        this.post('./api/fid', data).subscribe(() => {
            this.formdata.reset();
            this.ngOnInit();
            $('#myModal').modal('hide');
        });
    }

    search(form: NgForm) {
        this.searchUrl = './api/user/search' +
            '?role_id=' + form.value.role_id +
            '&store_id=' + form.value.store_id +
            '&f-id=' + form.value.f_id +
            '&username=' + form.value.username +
            '&phone=' + form.value.phone +
            '&email=' + form.value.email;
        this.page = 1;
        this.isearch = true;
        this.toSearch(this.searchUrl);
    }
    toSearch(url) {
        this.get(url + '&page=' + this.page).subscribe(response => this.handleData(response));
    }

    delete(id) {
        super.delete('./api/user/' + id);
    }

    enable(id, enable) {
        this.switchButton('./api/user/', id, enable);
    }
}
