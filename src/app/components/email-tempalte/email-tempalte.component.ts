import {Component} from '@angular/core';
import {BaseComponent} from '../../app/base.component';
import {NgForm} from '@angular/forms';
import {HttpHeaders} from '@angular/common/http';
declare var $: any;

const ParseHeaders = {
    headers: new HttpHeaders({
        'Content-Type': 'application/js; charset=UTF-8'
    })
};

@Component({
    selector: 'app-email-tempalte',
    templateUrl: './email-tempalte.component.html',
    styleUrls: ['./email-tempalte.component.css']
})
export class EmailTempalteComponent extends BaseComponent {
    id;
    name;
    mail;
    list_mail;
    dataMail;
    selected;
    formdataMail;
    notDeleteMail = ['mail_plan_request_user', 'mail_plan_admin', 'mail_cancel_plan'];
    init() {
        this.setFormAdd();
        this.getMail();
        this.loaded = true;
    }

    getMail() {
        this.get('./api/email-template/list').subscribe(res => {
            this.list_mail = res;
            this.list_mail = this.list_mail.response.data;
            if (this.list_mail) {
                this.dataMail = this.list_mail[0];
                this.selected = this.dataMail.id;
                this.setFormdata();
                this.patchForm();
            }
        });
    }

    patchForm() {
        // console.log(this.dataMail);
        if (this.dataMail) {
            this.formdata.patchValue({
                mail_id: this.dataMail.id,
                template: this.dataMail.template,
                subject: this.dataMail.title,
                content: this.dataMail.content
            });
        }
    }

    setFormAdd() {
        return this.formdataMail = this.fb.group({
            template: [''],
            title: [''],
        });
    }

    selectMail(id) {
        this.http.get('./api/email-template/' + id).subscribe(data => {
            this.dataMail = data['response'].data;
            this.patchForm();
        });
    }

    setFormdata() {
        // this.selected = 1;
        return this.formdata = this.fb.group({
            mail_id: '',
            template: '',
            subject: '',
            content: '',
        });
    }

    addMail(form: NgForm) {
        if (form.value.title !== '') {
            this.post('./api/email-template', JSON.stringify(form.value), ParseHeaders).subscribe(res => {
                this.handleSubmit(res, true);
            });
        }
        $('#addMailModal').modal('hide');
    }

    handleSubmit(res, isAdd = false) {
        super.getResponseMessageSubmit(res);
        if (this.success) {
            super.checkSubmit('email-template');
            // console.log()
            this.formdataMail.patchValue({
                title: '',
                template: ''
            });
            if (isAdd) {
                this.getMail();
                this.selectMail(res['response'].data.id);
            }

        }
    }

    onSubmit(data) {
        if (data.mail_id) {
            this.http.patch('./api/email-template/' + data.mail_id, JSON.stringify(data), ParseHeaders).subscribe(res => {
                this.handleSubmit(res);
            });
        }

    }

    protected get_current_template(): string {
        return $('#select_template').find(':selected').val();
    }

    deleteTemplate() {
        const selected_tempalte = this.get_current_template();
        let counter;
        // check mail cannot delete
        if ( this.list_mail) {
            for (counter = 0; counter < this.list_mail.length; counter++) {
                if ( this.list_mail[counter]['id'] == selected_tempalte) {
                    if (this.notDeleteMail.indexOf(this.list_mail[counter]['template']) !== -1) {
                        super.checkSubmit('email-template', this.translate.instant('email.not_delete'),
                            this.translate.instant('email.title'));
                        return;
                    }
                }
            }
        }

        super.delete_mail('./api/email-template/' + selected_tempalte, this.translate.instant('email.confirm_delete')
            , this.translate.instant('email.title'));
    }

}
