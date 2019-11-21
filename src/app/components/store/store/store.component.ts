import {Component, OnInit} from '@angular/core';
import {NgForm} from '@angular/forms';
import {BaseComponent} from '../../../app/base.component';

@Component({
    selector: 'app-store',
    templateUrl: './store.component.html',
    styleUrls: ['./store.component.css']
})

export class StoreComponent extends BaseComponent {
    attrImport = 'store';

    init() {
        this.refreshData();
    }

    refreshData() {
        this.isearch ? this.toSearch(this.searchUrl) :
        this.get('./api/store?page=' + this.page).subscribe(data => {
            this.handleData(data);
        });
    }

    search(form: NgForm) {
        this.searchUrl = './api/store/search?name=' + form.value.name
        + '&code=' + form.value.code;
        this.page = 1;
        this.isearch = true;
        this.toSearch(this.searchUrl);
    }
    toSearch(url) {
        this.get(url + '&page=' + this.page).subscribe(response => this.handleData(response));
    }

    delete(id) {
        super.delete('./api/store/' + id);
    }

    enable(id, enable) {
        this.switchButton('./api/store/', id, enable);
    }

}
