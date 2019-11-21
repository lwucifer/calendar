import {Component} from '@angular/core';
import {NgForm} from '@angular/forms';
import {PlanBaseComponent} from '../plan-base.component';

declare var $: any;

@Component({
    selector: 'app-reservation',
    templateUrl: './plan.component.html',
    styleUrls: ['./plan.component.css']
})
export class PlanComponent extends PlanBaseComponent {

    public dateTimeRange: Date[] = [];
    attrImport = 'plan';
    protected selectListCustomer = [];
    messenger_response = '';
    protected status_list_enable = [
        // {id: 1, name: '新しい予約'},
        {id: 2, name: '予約認識中'},
        {id: 3, name: 'キャンセル'}
    ];

    init() {
        this.status_list = [
            // {id: 1, name: '新しい予約'},
            {id: 2, name: '予約認識中'},
            {id: 3, name: 'キャンセル'},
            {id: 4, name: '予約完了'}
        ];

        this.get('./api/store/listSelect').subscribe(response => this.stores = this.parseHttpRes(response));
        this.get('./api/campaign/listSelect').subscribe(response => this.campaigns = this.parseHttpRes(response));
        this.refreshData();
    }

    getSelectedStatus(status) {
         const list = this.status_list.filter(e => e.id === parseInt(status, 0));
         return list.length > 0 ? list[0] : this.status_list_enable[0];
    }

    onSelectAllCustomer(isChecked) {
        if (isChecked.checked) {
            this.checkAllCheckbox();
            for (let i of this.results) {
                this.selectListCustomer.push(i.id);
            }
        } else {
            this.selectListCustomer = [];
            this.unCheckBox();
        }
    }

    onSelectCustomer(id, isChecked) {
        let index;
        if (isChecked.checked) {
            this.selectListCustomer.push(id);
        } else {
            index = this.selectListCustomer.findIndex(x => x === id);
            this.selectListCustomer.splice(index, 1);
        }
    }

    checkAllCheckbox() {
        $('.select-plan').prop('checked', true);
        $('.select-plan').attr('checked', true);
    }

    unCheckBox() {
        $('.select-all-plan').prop('checked', false);
        $('.select-all-plan').attr('checked', false);
        $('.select-plan').prop('checked', false);
        $('.select-plan').attr('checked', false);
    }

    getDataCustomer() {
        if ( this.selectListCustomer.length === 0) {
            super.notChecked('plan');
            return;
        }

        this.messenger_response = '';
        $('#messenger_response .modal-body').html('');
        this.post('./api/plan/updateCustomer', this.selectListCustomer).subscribe(res => {
            this.unCheckBox();
            this.handlesubmit(res);
        });
    }

    downloadPdf() {
        if ( this.selectListCustomer.length === 0) {
            super.notChecked('plan');
            return;
        }

        this.unCheckBox();
        window.location.href = './api/plan/export/pdf?list=' + this.selectListCustomer;
        this.selectListCustomer = [];
    }

    handlesubmit(data) {
        this.success = data.response.status.message === 'success';
        if (this.success) {
            const dataRes = data.response.data;
            let listId, listMes;
            this.selectListCustomer = [];
            const getCode = data.response.status.code.split(' ');
            if (getCode[0] === '200') {
                for (let i = 0; i < dataRes.length; i++) {
                    if (i !== 0) {
                        this.messenger_response += '<br/>';
                    }
                    listId = Object.keys(dataRes[i]);
                    listMes = dataRes[i][listId[0]];
                    this.messenger_response += listId[0] + ': ' + listMes;
                }
            } else {
                this.messenger_response = dataRes;
            }

            $('#messenger_response').modal('show');
        }
    }

    refreshData() {
        this.isearch ? this.toSearch(this.searchUrl) :
            this.get('./api/plan?page=' + this.page).subscribe(response => this.handleData(response));
    }

    search(form: NgForm) {
        const start = this.getdate(this.dateTimeRange[0]);
        const end = this.getdate(this.dateTimeRange[1]);
        this.searchUrl = './api/plan/search' +
            '?store_id=' + form.value.store_id +
            '&campaign_id=' + form.value.campaign_id +
            '&fid=' + form.value.fid +
            '&username=' + form.value.username +
            '&email=' + form.value.email +
            '&phone=' + form.value.phone +
            '&start_time=' + start +
            '&end_time=' + end +
            '&status=' + form.value.status;
        this.page = 1;
        this.isearch = true;
        this.toSearch(this.searchUrl);
    }

    toSearch(url) {
        this.get(url + '&page=' + this.page).subscribe(response => this.handleData(response));
    }

    delete(id) {
        super.delete('./api/plan/' + id);
    }

    showoption(data): string {
        let ret = '';
        let counter;
        const datas = JSON.parse(data[0]['content']);
        for (counter = 0; counter < datas.length; counter++) {
            const comma = ret === '' ? '' : ', ';
            ret = ret + comma + datas[counter]['name'];
        }
        return ret;
    }

    changeStatus(id, status) {
        this.post('api/plan/status/' + id + '/' + status, status).subscribe(() => this.refreshData());
    }

    onNext(): void {
        this.unCheckBox();
        this.selectListCustomer = [];
        this.page++;
        this.refreshData();
    }

    onPrev(): void {
        this.unCheckBox();
        this.selectListCustomer = [];
        this.page--;
        this.refreshData();
    }

    goToPage(n: number): void {
        this.unCheckBox();
        this.selectListCustomer = [];
        this.page = n;
        this.refreshData();
    }

}

