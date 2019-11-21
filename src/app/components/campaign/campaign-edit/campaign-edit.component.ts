import {Component} from '@angular/core';
import {CampaignBaseComponent} from '../campaign-base.component';

@Component({
    selector: 'app-campaign-edit',
    templateUrl: './../campaign-base.component.html',
    styleUrls: ['./campaign-edit.component.css']
})
export class CampaignEditComponent extends CampaignBaseComponent {
    init() {
        // get current id
        this.id = this.route.snapshot.paramMap.get('id');

        // init form
        this.initform();

        this.get('./api/campaign/' + this.id).subscribe(campaign => {

            if (!this.checkRequestSuccess(campaign)) {
                // redirect 404;
                this.router.navigate(['/404'], { skipLocationChange: true });
                return;
            }

            this.results = this.parseHttpRes(campaign);
            this.old_selected_photo = this.results['photo']['id'];
            if ( this.isManager()) {
                if ( this.results['create_by'] !== this.getInfoLoginUser().id.toString()) {
                    this.show_button = false;
                }
            }
            // get list store and photo type
            this.get('./api/store/enable_list').subscribe(store => {
                this.origin_store = this.parseHttpRes(store);
                this.get('./api/photo/list').subscribe(photo => {
                    this.list_photo = this.parseHttpRes(photo);
                    this.get('./api/email-template/list').subscribe(mail => {
                        this.list_template_mail = this.parseHttpRes(mail);
                        this.loaded = true;

                        // assign current data to form
                        this.assignform(this.results);

                        // update status
                        this.update_current_status();
                    });
                });
            });
        });

        this.json_photo_option = [];
    }

    onSubmit(data) {
        this.preSubmit(data);
        this.http.put('./api/campaign/' + this.id, data).subscribe(res => {
            this.handlesubmit(res);
        });
    }

    async_json_data() {
        let i, j, k;
        this.json_campaign_option = this.json.decode(this.campaign_content_value[this.current_option]);
        // async data if photo option change
        if (this.json_campaign_option) {
            for (i = 0; i < Object.keys(this.json_photo_option).length; i++) {
                for (j = 0; j < Object.keys(this.json_campaign_option).length; j++) {
                    if (this.json_photo_option[i]['id'] === this.json_campaign_option[j]['parent_id']) {
                        // assign id
                        this.json_photo_option[i]['id'] = this.json_campaign_option[j]['id'];
                        // assign price
                        for (k = 0; k < Object.keys(this.json_photo_option[i]['select']).length; k++) {
                            if (this.json_campaign_option[j]['select'][k]) {
                                this.json_photo_option[i]['select'][k]['holiday_price']
                                    = this.json_campaign_option[j]['select'][k]['holiday_price'];
                                this.json_photo_option[i]['select'][k]['weekday_price']
                                    = this.json_campaign_option[j]['select'][k]['weekday_price'];
                            }
                        }
                    }
                }
            }
        }
    }

    get_photo_json() {
        const id = this.get_id();
        let counter, i;
        let data;

        this.json_photo_option = [];
        if ((this.list_photo.length > 0) && (typeof id !== 'undefined')) {
            for (counter = 0; counter < this.list_photo[id].option.length; counter++) {
                data = this.json.decode(this.list_photo[id].option[counter]['content']);
                for (i = 0; i < Object.keys(data).length; i++) {
                    // create new ID for json
                    data[i]['parent_id'] = data[i]['id'];
                    // create new ID for json
                    if (this.saved_option[this.current_option] !== this.created_status) {
                        data[i]['id'] = this.json.create_id();
                    }
                }
                if (counter === 0) {
                    this.json_photo_option[0] = data;
                } else {
                    this.json_photo_option.push(data);
                }
            }
        }

        this.json_photo_option = this.json_photo_option[0];

        // async data with photo option
        if (this.saved_option[this.current_option] === this.created_status) {
            this.async_json_data();
        } else {
            this.parse_data();
        }

    }
}
