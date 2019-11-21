import { Injectable } from '@angular/core';
import {TranslateService} from '@ngx-translate/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ConfirmationDialogComponent } from './confirmation-dialog.component';

@Injectable()
export class ConfirmationDialogService {

  constructor(
      private modalService: NgbModal,
      private translate: TranslateService,
  ) { }

  public confirm(
    title: string,
    message: string,
    btnOkText: string = this.translate.instant('common.ok'),
    btnCancelText: string = this.translate.instant('common.cancel'),
    status: boolean = true,
    dialogSize: 'sm'|'lg' = 'sm'): Promise<boolean> {
      const modalRef = this.modalService.open(ConfirmationDialogComponent, { size: dialogSize });
      modalRef.componentInstance.title = title;
      modalRef.componentInstance.message = message;
      modalRef.componentInstance.btnOkText = btnOkText;
      modalRef.componentInstance.btnCancelText = btnCancelText;

      return modalRef.result;
    }
}
