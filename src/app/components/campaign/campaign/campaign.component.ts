import {Component} from '@angular/core';
import {NgForm} from '@angular/forms';
import {BaseComponent} from '../../../app/base.component';

@Component({
    selector: 'app-campaign',
    templateUrl: './campaign.component.html',
    styleUrls: ['./campaign.component.css']
})

export class CampaignComponent extends BaseComponent {
    photo;
    stores;
    campaigns = [];
    attrImport = 'campaign';
    show_action = true;
    campaignStores;
    campaignModal;
    init() {
        this.get('./api/store/listSelect').subscribe(response => this.stores = this.parseHttpRes(response));
        this.get('./api/photo/listSelect').subscribe(response => this.photo = this.parseHttpRes(response));
        this.get('./api/campaign/listSelect').subscribe(response => this.campaigns = this.parseHttpRes(response));
        this.refreshData();
    }
    refreshData() {
        this.isearch ? this.toSearch(this.searchUrl) :
        this.get('./api/campaign?page=' + this.page).subscribe(response => this.handleData(response));
    }
    search(form: NgForm) {
        this.searchUrl = './api/campaign/search' +
            '?id=' + form.value.campaign_id +
            '&code=' + form.value.code +
            '&store_id=' + form.value.store_id +
            '&photo_id=' + form.value.photo_id;
        this.page = 1;
        this.isearch = true;
        this.toSearch(this.searchUrl);
    }
    toSearch(url) {
        this.get(url + '&page=' + this.page).subscribe(response => this.handleData(response));
    }

    getStores(id, name) {
        this.get('./api/campaign/stores/' + id).subscribe(response => {
            this.campaignStores = response;
            this.campaignModal = name;
        });
    }

    delete(id) {
        super.delete('./api/campaign/' + id);
    }

    enable(id, enable) {
        this.switchButton('./api/campaign/', id, enable);
    }

    get_list_store(id) {
        let ret = '';

        this.results.forEach((value) => {
            if ( value.id === id) {
                if ( this.isManager()) {
                    value.store.forEach((val) => {
                        if ( val.id === this.getInfoLoginUser().store_id) {
                            ret = val.name;
                        }
                    });
                } else {
                    value.store.forEach((val) => {
                        ret += val.name + ',';
                    });
                    ret = ret.substring(0, ret.length - 1);
                }
            }
        });
        return ret;
    }

    copy(id) {
        const url = './api/campaign/copy/' + id + '?code=' + this.json.create_id();
        super.copyResponse(url , this.translate.instant('common.copy_message'));
    }

    check_condition(i) {
        let ret = true;
        if ( this.isManager()) {
            if ( i.create_by !== this.getInfoLoginUser().id.toString()) {
                ret = false;
            }
        }
        return ret;
    }

}
