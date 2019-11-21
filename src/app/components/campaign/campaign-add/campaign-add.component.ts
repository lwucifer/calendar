import {Component, OnInit} from '@angular/core';
import {CampaignBaseComponent} from '../campaign-base.component';

@Component({
    selector: 'app-campaign-add',
    templateUrl: './../campaign-base.component.html',
    styleUrls: ['./campaign-add.component.css'],
})

export class CampaignAddComponent extends CampaignBaseComponent {
    init() {
        // init form
        this.initform();
        // get list store and photo type
        this.get('./api/store/enable_list').subscribe(store => {
            this.origin_store = this.parseHttpRes(store);
            this.get('./api/photo/list').subscribe(photo => {
                this.list_photo = this.parseHttpRes(photo);
                this.get('./api/email-template/list').subscribe(mail => {
                    this.list_template_mail = this.parseHttpRes(mail);
                    this.loaded = true;
                });
            });
        });

        this.addOption(true);
        this.currentdate[0] = new Date(Date.now());
        this.currentdate[1] = new Date(Date.now());
        this.dateTimeRange[0] = this.currentdate;
        this.created_option[this.current_option] = this.not_create_status;
        this.title = this.translate.instant('campaign.campaign_add.title');
    }

    onSubmit(data) {
        data['create_by'] = this.getInfoLoginUser().id;
        this.preSubmit(data);
        this.post('./api/campaign', data).subscribe(res => {
            this.handlesubmit(res);
        });
    }

    get_photo_json() {
        const id = this.get_id();
        let counter, i;
        let data;

        this.json_photo_option = [];
        if ((this.list_photo.length > 0) && (typeof id !== 'undefined')) {
            this.created_option[this.current_option] = this.created_status;
            for (counter = 0; counter < this.list_photo[id].option.length; counter++) {
                data = this.json.decode(this.list_photo[id].option[counter]['content']);
                for (i = 0; i < Object.keys(data).length; i++) {
                    // create new ID for json
                    data[i]['parent_id'] = data[i]['id'];
                    data[i]['id'] = this.json.create_id();
                }
                if (counter === 0) {
                    this.json_photo_option[0] = data;
                } else {
                    this.json_photo_option.push(data);
                }
            }
        }

        this.json_photo_option = this.json_photo_option[0];

        this.parse_data();
    }
}
