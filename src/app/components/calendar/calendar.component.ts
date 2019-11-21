import {Component, ViewChild } from '@angular/core';
import {BaseComponent} from '../../app/base.component';
import { OptionsInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { CalendarComponent } from 'ng-fullcalendar';
import {el} from '@angular/platform-browser/testing/src/browser_util';
declare var $: any;

@Component({
    selector: 'app-calendar',
    templateUrl: './calendar.component.html',
    styleUrls: ['./calendar.component.css']
})

export class ManagerCalendarComponent extends BaseComponent {
    @ViewChild('fullcalendar') fullcalendar: CalendarComponent;
    options: OptionsInput;
    eventsModel: any;

    dataBook = [];
    public dataDate;
    optionDate = [];
    public plans;
    dateType;
    date;
    status = '';
    show = false;
    arrayDate;
    rangeTime;
    stroreName;
    private status_list = [
        // {id: 1, name: '新しい予約'},
        {id: 2, name: '予約認識中'},
        {id: 3, name: 'キャンセル'},
        {id: 4, name: '予約完了'}
    ];

    init() {
        this.id = this.route.snapshot.paramMap.get('id');
        const self = this;
        this.get('/store/calendar/' + self.id).subscribe(response => {
            const parse = response['response']['data']['calendar'];
            self.stroreName = response['response']['data']['store_name'];
            self.dataBook = response['response']['data']['data_book'];
            self.arrayDate = Object.keys(self.dataBook);
            self.options = {
                editable: true,
                events: parse,
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                plugins: [ dayGridPlugin, interactionPlugin ],
                firstDay: 1,
                locale: '' +
                    '',
            };
            setTimeout(() => {
                self.setSpecial();
            }, 99);
        });
    }

    setSpecial() {
        const self = this;
        for (let i = 0; i < self.arrayDate.length; i++) {
            const count = self.countPlan( self.dataBook[self.arrayDate[i]] );
            $('.fc-day[data-date=' + self.arrayDate[i] + ']').html('<span>' + count + '</span>');
            if ( self.checkNew( self.dataBook[self.arrayDate[i]] ) ) {
                $('.fc-day[data-date=' + self.arrayDate[i] + ']').css({
                    'background': '#DEF5AA'
                }).addClass('new');
            }
        }
    }

    countPlan(data) {
        let i = 0;
        for (const index of Object.keys(data.plan_list)) {
            data.plan_list[index].forEach( () => {
                i++;
            });
        }
        return i;
    }

    checkNew(data) {
        let status = false;
        for (const index of Object.keys(data.plan_list)) {
            data.plan_list[index].forEach( (myObject) => {
                if (myObject.status === 2) {
                    status = true;
                }
            });
        }
        return status;
    }

    toTimes(date) {
        this.date = date;
        if (this.dataBook[date]) {
            this.dataDate = this.dataBook[date];
            let plan = this.dataDate['plan_list'];
            plan = Object.keys(plan).map(function(key) {
                return [plan[key]];
            });
            this.plans = plan;
            this.rangeTime = this.dataDate['range_time'];
            this.dateType = this.dataDate['date_type'] === 1;
            this.show = true;
        } else {
            this.plans = [];
            this.rangeTime = [];
            this.dataDate = [];
            this.show = false;
        }
    }

    dateClick(info) {
        const days = ['sun', 'mon', 'fri', 'thu', 'wed', 'tue', 'sat'];
        const today = info.date.toLocaleDateString('ja-JA', {weekday: 'short'});
        const specialWeekend = 'fc-' + days[info.date.getDay()];
        $('#idate').html(info.date.getMonth() + 1 + '月' + info.date.getDate() + '日' +
            '(<span class="' + specialWeekend + '">' + today + '</span>)');
        this.toTimes(info.dateStr);

        setTimeout(() => {
            const rule = $('.range-time tr').height();
            $('.list_plan').css({
                'height': this.rangeTime.length * rule,
            });
            $('.item').each(function() {
                const top = $(this).data('top') * rule + 1;
                const height = $(this).data('height') * rule - 1;
                $(this).css({
                    'top': top,
                    'height': height,
                });
            });

            const col = $('.col-custom').width();
            $('.col-custom').each(function() {
                let max = 1;
                $(this).children('.item-top').each(function() {
                    const top = $(this).data('top');
                    let i = 0;
                    $(this).parent().children('.item-top[data-top="' + top + '"]').each(function() {
                        i++;
                        $(this).children('.item ').css({
                            'left': (i - 1) * col,
                        }).attr('data-left', i);
                        $(this).attr('data-left', i);
                        if (i > max) { max = i; }
                        $(this).parent().css({
                            'width': (max * col) + 2,
                        }).attr('data-max', max);
                    });
                });
            });
           this.setLeft(1, col);
        }, 9);
    }

    setLeft(e, col) {
        let check = false;
        $('.col-custom').each(function() {
            $(this).children('.item-top[data-left="' + e + '"]').each(function() {
                const top = $(this).data('top');
                const item = $(this).data('item');
                const bottom = $(this).data('top') + $(this).data('height');
                const height = $(this).data('height');
                for (let index = top; index < bottom; index++) {
                    $(this).parent().children('.item-top[data-top="' + index + '"][data-left="' + e + '"]').each(function() {
                        const left_current = $(this).data('left');
                        if ( $(this).data('item') !== item ) {
                            if ($(this).data('height') < height) {
                                $(this).children('.item ').css({
                                    'left': e * col,
                                }).attr('data-left', e + 1);
                                $(this).attr('data-left', e + 1);
                                if (e + 1 > $(this).parent().data('max')) {
                                    $(this).parent().css({
                                        'width': ((e + 1) * col) + 2,
                                    });
                                }
                                check = true;
                            }
                            if ($(this).data('height') === height) {
                                $(this).children('.item ').css({
                                    'left': left_current * col,
                                }).attr('data-left', left_current + 1);
                                $(this).attr('data-left', left_current + 1);
                                if (left_current + 1 > $(this).parent().data('max')) {
                                    $(this).parent().css({
                                        'width': ((left_current + 1) * col) + 2,
                                    });
                                }
                                check = true;
                            }
                        }
                    });
                }
            });
        });
        if (check) { this.setLeft(e + 1, col); }
    }

    get yearMonth(): string {
        const dateObj = new Date();
        return dateObj.getUTCFullYear() + '-' + (dateObj.getUTCMonth() + 1);
    }
}

