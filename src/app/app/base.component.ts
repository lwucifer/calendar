import {Component, OnInit} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {ActivatedRoute, Router} from '@angular/router';
import {StorageComponent} from './storage.component';
import {ManagerComponent} from './manager.component';
import {ConfirmationDialogService} from '../services/confirmation-dialog/confirmation-dialog.service';
import {DateTimeAdapter} from 'ng-pick-datetime';
import {TranslateService} from '@ngx-translate/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {JsonhelperService} from '../services/jsonhelper/jsonhelper.service';
import {DragulaService} from 'ng2-dragula';


const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/json'
    })
};

@Component({
    selector: 'app-app'
})

export class BaseComponent extends ManagerComponent implements OnInit {
    protected id;
    protected results;
    protected pages = [];
    protected message = [];
    protected loaded = false;
    protected formdata: FormGroup;
    protected success: boolean;
    protected ok;
    protected page = 1;
    protected order = 'id';
    protected reverse = true;
    protected title = 'Calendar System';
    protected response;
    protected dateformat = 'yyyy-MM-dd';
    protected object;
    protected isearch = false;
    protected searchUrl;

    constructor(
        protected http: HttpClient,
        protected router: Router,
        protected confirmationDialogService: ConfirmationDialogService,
        protected dateTimeAdapter: DateTimeAdapter<any>,
        protected translate: TranslateService,
        protected fb: FormBuilder,
        protected route: ActivatedRoute,
        protected json: JsonhelperService,
        protected drag: DragulaService
    ) {
        super();
        this.getAuthInfo();
        this.object = this;
        dateTimeAdapter.setLocale('ja-JP');
        translate.setDefaultLang('ja');
    }

    ngOnInit() {
        this.init();
    }

    init() {}

    getAuthInfo() {
        if (this.getInfoLoginUser() !== null) {
            return true;
        }

        this.get('./api/user-role').subscribe(response => {
            StorageComponent.setItemInStorage(this.storageKey.CHECK_REDIRECT_LOGIN, false);
            StorageComponent.setItemInStorage(this.storageKey.USER_INFO, this.parseHttpRes(response));
        });
    }

    handleData(data) {
        this.pages = data.response.data ? data.response.data.pagination : 10;
        this.results = data.response.data ? data.response.data.list : '';
    }

    goToPage(n: number): void {
        this.page = n;
        this.refreshData();
    }

    onNext(): void {
        this.page++;
        this.refreshData();
    }

    onPrev(): void {
        this.page--;
        this.refreshData();
    }

    setOrder(value: string) {
        if (this.order === value) {
            this.reverse = !this.reverse;
        }
        this.order = value;
    }

    switchButton(path, id, enable) {
        const url = path + (enable ? 'disable/' : 'enable/') + id;
        this.post(url, id).subscribe(() => this.refreshData());
    }

    delete(path,
           message = this.translate.instant('common.delete_message'),
           title = this.translate.instant('common.delete_title')) {
        this.confirmationDialogService.confirm(title, message)
            .then((confirmed) => {
                if (confirmed) {
                    this.del(path).subscribe(() => {
                        this.refreshData();
                    });
                }
            }).catch(() => this.ok = false);
    }

    delete_mail(path,
           message = this.translate.instant('common.delete_message'),
           title = this.translate.instant('common.delete_title')) {
        this.confirmationDialogService.confirm(title, message)
            .then((confirmed) => {
                if (confirmed) {
                    this.del(path).subscribe(() => {
                        this.refresh();
                    });
                }
            }).catch(() => this.ok = false);
    }

    checkRequestSuccess(res) {
        return res['response']['status']['message'] === 'success';
    }

    parseHttpRes(response, data = 'data') {
        return response['response'][data];
    }

    // Router redirect
    redirectNavigate(path = [], params = {}) {
        return this.router.navigate(path, params);
    }

    // Http defined
    get(path) {
        return this.http.get(path);
    }

    getSubscribe(path, result) {
        return this.get(path).subscribe(response => result = this.parseHttpRes(response));
    }

    post(url, data, parseHeaders = ParseHeaders) {
        return this.http.post(url, data, parseHeaders);
    }

    del(path, parseHeaders = ParseHeaders) {
        return this.http.delete(path, parseHeaders);
    }

    patch(path, data, parseHeaders = ParseHeaders) {
        return this.http.patch(path, data, parseHeaders);
    }

    reload() {
        this.refreshData();
    }

    refreshData() {}

    checkSubmit( route,
                 message = this.translate.instant('common.submit_success_message'),
                 title = this.translate.instant('common.submit_title')) {
        this.confirmationDialogService.confirm(title, message, this.translate.instant('common.ok'), '')
            .then((confirmed) => {
                this.router.navigate([route]);
            }).catch(() => this.router.navigate([route]));
    }

    notChecked( route,
                 message = this.translate.instant('common.not_checked_message'),
                 title = this.translate.instant('common.not_checked_title')) {
        this.confirmationDialogService.confirm(title, message, this.translate.instant('common.ok'), '')
            .then((confirmed) => {
                this.router.navigate([route]);
            }).catch(() => this.router.navigate([route]));
    }

    getResponseMessageSubmit (response) {
        this.results = response;
        this.success = this.results.response.status.message === 'success';
        return this.message = this.success ? '' : this.results.response.status.message;
    }

    copyResponse(url, message, title = this.translate.instant('common.copy_title')) {
        this.confirmationDialogService.confirm(title, message)
            .then((confirmed) => {
                if (confirmed) {
                    this.get(url).subscribe(res => {
                        this.refreshData();
                    });
                }
            }).catch(() => this.ok = false);
    }

    // date time format
    formatDate(date) {
        let year, month, day;
        const d = new Date(date);
        month = '' + (d.getMonth() + 1);
        day = '' + d.getDate();
        year = d.getFullYear();

        if (month.length < 2) {
            month = '0' + month;
        }
        if (day.length < 2) {
            day = '0' + day;
        }
        return [year, month, day].join('-');
    }

    formatTime(date) {
        let hour, minutes;
        const d = new Date(date);
        hour = '' + (d.getHours());
        minutes = '' + d.getMinutes();

        if (hour.length < 2) {
            hour = '0' + hour;
        }
        if (minutes.length < 2) {
            minutes = '0' + minutes;
        }
        return [hour, minutes, '00'].join(':');
    }

    formatDateTime(date) {
        return this.formatDate(date) + ' ' + this.formatTime(date);
    }

    isValidDate(date) {
        const d = new Date(date);
        if ( !isNaN(d.getFullYear()) &&
            !isNaN(d.getDate()) &&
            !isNaN(d.getMonth()) &&
            !isNaN(d.getHours()) &&
            !isNaN(d.getMinutes()) &&
            !isNaN(d.getSeconds())) {

            // check default date of datepicker 1970/01/01 07:00:00
            if ( d.getFullYear().toString() === '1970' &&
                d.getMonth().toString() === '0' &&
                d.getDate().toString() === '1' &&
                d.getHours().toString() === '7' &&
                d.getMinutes().toString() === '0' &&
                d.getSeconds().toString() === '0') {
                return false;
            }

            return true;
        }

        return false;
    }

    getdate(string_date) {
        return this.isValidDate(string_date) ? this.formatDateTime(string_date) : '';
    }

    getday(string_day) {
        return this.isValidDate(string_day) ? this.formatDate(string_day) : '';
    }

    getbasetime() {
        return new Date(this.formatDate(new Date()) + ' 00:00:00');
    }
    getbasetimeStart() {
        return new Date(this.formatDate(new Date()) + ' 08:00:00');
    }
    getbasetimeEnd() {
        return new Date(this.formatDate(new Date()) + ' 18:00:00');
    }

    showTime(timestring) {
        return timestring == null ? timestring : new Date( this.formatDate(new Date()) + ' ' + timestring);
    }

    getTime(timestring) {
        return timestring = this.formatTime(timestring);
    }

    refresh(): void {
        window.location.reload();
    }
}
