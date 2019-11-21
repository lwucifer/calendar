import {Component} from '@angular/core';
import {BaseComponent} from '../../app/base.component';
import {HttpHeaders} from '@angular/common/http';

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-admin',
    templateUrl: './admin.component.html',
    styleUrls: ['./admin.component.css']
})
export class AdminComponent extends BaseComponent {
    cash;
    manager;
    tax;
    ip = [];
    ipList;
    addTax = [];
    addCash = [];
    removeIp = [];
    inputIp;
    resultUpdate;

    init() {
        this.get('./api/admin/').subscribe(data => {
            this.results = this.parseHttpRes(data);
            this.tax = JSON.parse(this.results.tax);
            this.manager = JSON.parse(this.results.manager);
            this.cash = JSON.parse(this.results.cash);
            this.ipList = JSON.parse(this.results.ip);
            if (this.ipList.length > 0) {
                this.ipList = JSON.parse(this.ipList[0].ip_address);
                this.ip = [...this.ipList.ip];
            }
            this.formdata = this.fb.group({
                tax: [this.tax],
                ip: [this.ip],
                is_enable: [this.manager[0].enable_ip],
                memo: [this.manager[0].memo],
                cash: [this.cash],
                addTax: [this.addTax],
                addCash: [this.addCash],
            });
            this.loaded = true;
        });
    }

    addMoreTax() {
        // this.tax.push(this.defaultTax);
        this.addTax.push({
            start_date_use: '',
            tax_percent: ''
        });
    }

    addMoreCash() {
        this.addCash.push({
            name: '',
        });
    }

    moveItems() {
        this.ip.push([...this.inputIp]);
        this.inputIp = '';
    }

    removeItems() {
        let removeIpList;
        removeIpList = [...this.removeIp];
        for (let i = 0; i < removeIpList.length; i++) {
            for (let j = 0; j < this.ip.length; j++) {
                if (this.ip[j] === removeIpList[i]) {
                    this.ip.splice(j, 1);
                }
            }
        }
        this.removeIp = [];
    }

    handlesubmit(data) {
        super.getResponseMessageSubmit(data);
    }

    presubmit(data) {
        data.tax.forEach(tax => {
            tax['start_date_use'] = super.getday(tax['start_date_use']);
        });

        data.addTax.forEach(tax => {
            tax['start_date_use'] = super.getday(tax['start_date_use']);
        });
    }

    onSubmit(data) {
        this.presubmit(data);
        this.http.post('./api/admin', JSON.stringify(data), ParseHeaders).subscribe(res => {
            this.resultUpdate = res;
            this.handlesubmit(res);
            if (this.success) {
                super.checkSubmit('admin' , this.translate.instant('common.submit_success_message'));
                this.resetDataForm();
            } else {
                this.ip = [...this.ipList.ip];
            }
        });
    }

    resetDataForm() {
        this.addTax = [];
        this.addCash = [];
        this.removeIp = [];
        this.inputIp = '';
        this.message = null;
        this.ip = [];
        this.init();
    }
}
