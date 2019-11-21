import {Component, OnInit} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {NgForm} from '@angular/forms';
import {BaseComponent} from '../../../app/base.component';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-photo',
    templateUrl: './photo.component.html',
    styleUrls: ['./photo.component.css']
})

export class PhotoComponent extends BaseComponent {
    attrImport = 'photo';
    reverse = true;
    init() {
        this.refreshData();
    }

    refreshData() {
        this.isearch ? this.toSearch(this.searchUrl) :
        this.get('./api/photo?page=' + this.page).subscribe(res => {this.handleData(res); });
    }

    delete(id) {
        super.delete('./api/photo/' + id);
    }

    search(form: NgForm) {
        this.searchUrl = './api/photo/search?name=' + form.value.store_name;
        this.page = 1;
        this.isearch = true;
        this.toSearch(this.searchUrl);
    }
    toSearch(url) {
        this.get(url + '&page=' + this.page).subscribe(response => this.handleData(response));
    }

    enable(id, enable) {
        this.switchButton('./api/photo/', id, enable);
    }
}
