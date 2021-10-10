$(function() {

	// Set the default dates
	var startDate	= Date.create().addDays(-6),	// 7 days ago
		endDate		= Date.create(); 				// today

	var range = $('#range');

	// Show the dates in the range input
	range.val(startDate.format('{MM}/{dd}/{yyyy}') + ' - ' + endDate.format('{MM}/{dd}/{yyyy}'));

	// Load chart
	ajaxLoadChart(startDate,endDate);
	
	range.daterangepicker({
		
		startDate: startDate,
		endDate: endDate,
		
		ranges: {
            'Today': ['today', 'today'],
            'Yesterday': ['yesterday', 'yesterday'],
            'Last 7 Days': [Date.create().addDays(-6), 'today'],
            'Last 30 Days': [Date.create().addDays(-29), 'today']
        }
	},function(start, end){
		
		ajaxLoadChart(start, end);
		
	});
	
	// The tooltip shown over the chart
	var tt = $('<div class="ex-tooltip">').appendTo('body'),
		topOffset = -32;

	var data = {
		"xScale" : "time",
		"yScale" : "linear",
		"main" : [{
			className : ".stats",
			"data" : []
		}]
	};

	var opts = {
		paddingLeft : 50,
		paddingTop : 20,
		paddingRight : 10,
		axisPaddingLeft : 25,
		tickHintX: 9, // How many ticks to show horizontally

		dataFormatX : function(x) {
			
			// This turns converts the timestamps coming from
			// ajax.php into a proper JavaScript Date object
			
			return Date.create(x);
		},

		tickFormatX : function(x) {
			
			// Provide formatting for the x-axis tick labels.
			// This uses sugar's format method of the date object. 

			return x.format('{MM}/{dd}');
		},
		
		"mouseover": function (d, i) {
			var pos = $(this).offset();
			
			tt.text(d.x.format('{Month} {ord}') + ': ' + d.y).css({
				
				top: topOffset + pos.top,
				left: pos.left
				
			}).show();
		},
		
		"mouseout": function (x) {
			tt.hide();
		}
	};

	// Create a new xChart instance, passing the type
	// of chart a data set and the options object
	
	var chart = new xChart('line-dotted', data, '#chart' , opts);
	
	// Function for loading data via AJAX and showing it on the chart
	function ajaxLoadChart(startDate,endDate) {

		// If no data is passed (the chart was cleared)
		
		if(!startDate || !endDate){
			chart.setData({
				"xScale" : "time",
				"yScale" : "linear",
				"main" : [{
					className : ".stats",
					data : []
				}]
			});
			
			return;
		}

		// Otherwise, issue an AJAX request

		$.getJSON(site_url + '/ajax/chart.php', {
			
			start:	startDate.format('{yyyy}-{MM}-{dd}'),
			end:	endDate.format('{yyyy}-{MM}-{dd}')
			
		}, function(data) {
			
			var set = [];
			$.each(data, function() {
				set.push({
					x : this.label,
					y : parseInt(this.value, 10)
				});
			});
			
			chart.setData({
				"xScale" : "time",
				"yScale" : "linear",
				"main" : [{
					className : ".stats",
					data : set
				}]
			});

		});
	}
});

/**
* @version: 1.0.1
* @author: Dan Grossman http://www.dangrossman.info/
* @date: 2012-08-20
* @copyright: Copyright (c) 2012 Dan Grossman. All rights reserved.
* @license: Licensed under Apache License v2.0. See http://www.apache.org/licenses/LICENSE-2.0
* @website: http://www.improvely.com/
*/
!function ($) {

    var DateRangePicker = function (element, options, cb) {
        var hasOptions = typeof options == 'object'
        var localeObject;

        //state
        this.startDate = Date.create('today');
        this.endDate = Date.create('today');
        this.minDate = false;
        this.maxDate = false;
        this.changed = false;
        this.cleared = false;
        this.ranges = {};
        this.opens = 'right';
        this.cb = function () { };
        this.format = '{MM}/{dd}/{yyyy}';
        this.separator = ' - ';
        this.showWeekNumbers = false;
        this.buttonClasses = ['btn-success'];
        this.locale = {
            applyLabel: 'Apply',
            clearLabel:"Clear",
            fromLabel: 'From',
            toLabel: 'To',
            weekLabel: 'W',
            customRangeLabel: 'Custom Range',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 0
        };

        localeObject = this.locale;

        this.leftCalendar = {
            month: Date.create('today').set({ day: 1, month: this.startDate.getMonth(), year: this.startDate.getFullYear() }),
            calendar: Array()
        };

        this.rightCalendar = {
            month: Date.create('today').set({ day: 1, month: this.endDate.getMonth(), year: this.endDate.getFullYear() }),
            calendar: Array()
        };

        //element that triggered the date range picker
        this.element = $(element);

        if (this.element.hasClass('pull-right'))
            this.opens = 'left';

        if (this.element.is('input')) {
            this.element.on({
                click: $.proxy(this.show, this),
                focus: $.proxy(this.show, this)
            });
        } else {
            this.element.on('click', $.proxy(this.show, this));
        }

        if (hasOptions) {
            if(typeof options.locale == 'object') {
                $.each(localeObject, function (property, value) {
                    localeObject[property] = options.locale[property] || value;
                });
            }
        }

        var DRPTemplate = '<div class="daterangepicker dropdown-menu">' +
                '<div class="calendar left"></div>' +
                '<div class="calendar right"></div>' +
                '<div class="ranges">' +
                  '<div class="range_inputs">' +
                    '<div class="daterangepicker_start_input" style="float: left">' +
                      '<label for="daterangepicker_start">' + this.locale.fromLabel + '</label>' +
                      '<input class="input-mini" type="text" name="daterangepicker_start" value="" disabled="disabled" />' +
                    '</div>' +
                    '<div class="daterangepicker_end_input" style="float: left; padding-left: 11px">' +
                      '<label for="daterangepicker_end">' + this.locale.toLabel + '</label>' +
                      '<input class="input-mini" type="text" name="daterangepicker_end" value="" disabled="disabled" />' +
                    '</div>' +
                    '<button class="btn btn-small btn-success applyBtn" disabled="disabled">' + this.locale.applyLabel + '</button>&nbsp;' +
                    '<button class="btn btn-small clearBtn">' + this.locale.clearLabel + '</button>' +
                  '</div>' +
                '</div>' +
              '</div>';

        this.container = $(DRPTemplate).appendTo('body');

        if (hasOptions) {

            if (typeof options.format == 'string')
                this.format = options.format;

            if (typeof options.separator == 'string')
                this.separator = options.separator;

            if (typeof options.startDate == 'string')
                this.startDate = Date.create(options.startDate);

            if (typeof options.endDate == 'string')
                this.endDate = Date.create(options.endDate);

            if (typeof options.minDate == 'string')
                this.minDate = Date.create(options.minDate);

            if (typeof options.maxDate == 'string')
                this.maxDate = Date.create(options.maxDate);


            if (typeof options.startDate == 'object')
                this.startDate = options.startDate;

            if (typeof options.endDate == 'object')
                this.endDate = options.endDate;

            if (typeof options.minDate == 'object')
                this.minDate = options.minDate;

            if (typeof options.maxDate == 'object')
                this.maxDate = options.maxDate;

            if (typeof options.ranges == 'object') {
                for (var range in options.ranges) {

                    var start = options.ranges[range][0];
                    var end = options.ranges[range][1];

                    if (typeof start == 'string')
                        start = Date.create(start);

                    if (typeof end == 'string')
                        end = Date.create(end);

                    // If we have a min/max date set, bound this range
                    // to it, but only if it would otherwise fall
                    // outside of the min/max.
                    if (this.minDate && start < this.minDate)
                        start = this.minDate;

                    if (this.maxDate && end > this.maxDate)
                        end = this.maxDate;

                    // If the end of the range is before the minimum (if min is set) OR
                    // the start of the range is after the max (also if set) don't display this
                    // range option.
                    if ((this.minDate && end < this.minDate) || (this.maxDate && start > this.maxDate))
                    {
                        continue;
                    }

                    this.ranges[range] = [start, end];
                }

                var list = '<ul>';
                for (var range in this.ranges) {
                    list += '<li>' + range + '</li>';
                }
                list += '<li>' + this.locale.customRangeLabel + '</li>';
                list += '</ul>';
                this.container.find('.ranges').prepend(list);
            }

            // update day names order to firstDay
            if (typeof options.locale == 'object') {
                if (typeof options.locale.firstDay == 'number') {
                    this.locale.firstDay = options.locale.firstDay;
                    var iterator = options.locale.firstDay;
                    while (iterator > 0) {
                        this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift());
                        iterator--;
                    }
                }
            }

            if (typeof options.opens == 'string')
                this.opens = options.opens;

            if (typeof options.showWeekNumbers == 'boolean') {
                this.showWeekNumbers = options.showWeekNumbers;
            }

            if (typeof options.buttonClasses == 'string') {
                this.buttonClasses = [options.buttonClasses];
            }

            if (typeof options.buttonClasses == 'object') {
                this.buttonClasses = options.buttonClasses;
            }

        }

        //apply CSS classes to buttons
        var c = this.container;
        $.each(this.buttonClasses, function (idx, val) {
            c.find('button').addClass(val);
        });

        if (this.opens == 'right') {
            //swap calendar positions
            var left = this.container.find('.calendar.left');
            var right = this.container.find('.calendar.right');
            left.removeClass('left').addClass('right');
            right.removeClass('right').addClass('left');
        }

        if (typeof options == 'undefined' || typeof options.ranges == 'undefined')
            this.container.find('.calendar').show();

        if (typeof cb == 'function')
            this.cb = cb;

        this.container.addClass('opens' + this.opens);

        //event listeners
        this.container.on('mousedown', $.proxy(this.mousedown, this));
        this.container.find('.calendar').on('click', '.prev', $.proxy(this.clickPrev, this));
        this.container.find('.calendar').on('click', '.next', $.proxy(this.clickNext, this));
        this.container.find('.ranges').on('click', 'button.applyBtn', $.proxy(this.clickApply, this));
        this.container.find('.ranges').on('click', 'button.clearBtn', $.proxy(this.clickClear, this));

        this.container.find('.calendar').on('click', 'td.available', $.proxy(this.clickDate, this));
        this.container.find('.calendar').on('mouseenter', 'td.available', $.proxy(this.enterDate, this));
        this.container.find('.calendar').on('mouseleave', 'td.available', $.proxy(this.updateView, this));

        this.container.find('.ranges').on('click', 'li', $.proxy(this.clickRange, this));
        this.container.find('.ranges').on('mouseenter', 'li', $.proxy(this.enterRange, this));
        this.container.find('.ranges').on('mouseleave', 'li', $.proxy(this.updateView, this));

        this.element.on('keyup', $.proxy(this.updateFromControl, this));

        this.updateView();
        this.updateCalendars();

    };

    DateRangePicker.prototype = {

        constructor: DateRangePicker,

        mousedown: function (e) {
            e.stopPropagation();
            e.preventDefault();
        },

        updateView: function () {
            this.leftCalendar.month.set({ month: this.startDate.getMonth(), year: this.startDate.getFullYear() });
            this.rightCalendar.month.set({ month: this.endDate.getMonth(), year: this.endDate.getFullYear() });

            this.container.find('input[name=daterangepicker_start]').val(this.startDate.format(this.format));
            this.container.find('input[name=daterangepicker_end]').val(this.endDate.format(this.format));

            if (this.startDate.is(this.endDate) || this.startDate.isBefore(this.endDate)) {
                this.container.find('button.applyBtn').removeAttr('disabled');
            } else {
                this.container.find('button.applyBtn').attr('disabled', 'disabled');
            }
        },

        updateFromControl: function () {
            if (!this.element.is('input')) return;

            var dateString = this.element.val().split(this.separator);
            var start = Date.create(dateString[0]);
            var end = Date.create(dateString[1]);

            if (start == null || end == null) return;
            if (end.isBefore(start)) return;

            this.startDate = start;
            this.endDate = end;

            this.updateView();
            this.cb(this.startDate, this.endDate);
            this.updateCalendars();
        },

        notify: function () {
            if (!this.cleared) {
              this.updateView();
            }

            if (this.element.is('input')) {
                this.element.val(this.cleared ? '' : this.startDate.format(this.format) + this.separator + this.endDate.format(this.format));
            }
            var arg1 = (this.cleared ? null : this.startDate),
                arg2 = (this.cleared ? null : this.endDate);
            this.cleared = false;
            this.cb(arg1,arg2);
        },

        move: function () {
            if (this.opens == 'left') {
                this.container.css({
                    top: this.element.offset().top + this.element.outerHeight(),
                    right: $(window).width() - this.element.offset().left - this.element.outerWidth(),
                    left: 'auto'
                });
            } else {
                this.container.css({
                    top: this.element.offset().top + this.element.outerHeight(),
                    left: this.element.offset().left,
                    right: 'auto'
                });
            }
        },

        show: function (e) {
            this.container.show();
            this.move();

            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }

            this.changed = false;

            this.element.trigger('shown',{target:e.target,picker:this});

            $(document).on('mousedown', $.proxy(this.hide, this));
        },

        hide: function (e) {
            this.container.hide();
            $(document).off('mousedown', this.hide);

            if (this.changed) {
                this.changed = false;
                this.notify();
            }
        },

        enterRange: function (e) {
            var label = e.target.innerHTML;
            if (label == this.locale.customRangeLabel) {
                this.updateView();
            } else {
                var dates = this.ranges[label];
                this.container.find('input[name=daterangepicker_start]').val(dates[0].format(this.format));
                this.container.find('input[name=daterangepicker_end]').val(dates[1].format(this.format));
            }
        },

        clickRange: function (e) {
            var label = e.target.innerHTML;
            if (label == this.locale.customRangeLabel) {
                this.container.find('.calendar').show();
            } else {
                var dates = this.ranges[label];

                this.startDate = dates[0];
                this.endDate = dates[1];

                this.leftCalendar.month.set({ month: this.startDate.getMonth(), year: this.startDate.getFullYear() });
                this.rightCalendar.month.set({ month: this.endDate.getMonth(), year: this.endDate.getFullYear() });
                this.updateCalendars();

                this.changed = true;

                this.container.find('.calendar').hide();
                this.hide();
            }
        },

        clickPrev: function (e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.addMonths(-1);
            } else {
                this.rightCalendar.month.addMonths(-1);
            }
            this.updateCalendars();
        },

        clickNext: function (e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.addMonths(1);
            } else {
                this.rightCalendar.month.addMonths(1);
            }
            this.updateCalendars();
        },

        enterDate: function (e) {

            var title = $(e.target).attr('title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');

            if (cal.hasClass('left')) {
                this.container.find('input[name=daterangepicker_start]').val(this.leftCalendar.calendar[row][col].format(this.format));
            } else {
                this.container.find('input[name=daterangepicker_end]').val(this.rightCalendar.calendar[row][col].format(this.format));
            }

        },

        clickDate: function (e) {
            var title = $(e.target).attr('title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');

            if (cal.hasClass('left')) {
                startDate = this.leftCalendar.calendar[row][col];
                endDate = this.endDate;
                this.element.trigger('clicked',{
                  dir: 'left',
                  picker: this
                });
            } else {
                startDate = this.startDate;
                endDate = this.rightCalendar.calendar[row][col];
                this.element.trigger('clicked',{
                  dir: 'right',
                  picker: this
                });
            }

            cal.find('td').removeClass('active');

            if (startDate.is(endDate) || startDate.isBefore(endDate)) {
                $(e.target).addClass('active');
                if (!startDate.is(this.startDate) || !endDate.is(this.endDate))
                    this.changed = true;
                this.startDate = startDate;
                this.endDate = endDate;
            }
            else if (startDate.isAfter(endDate)) {
                $(e.target).addClass('active');
                this.changed = true;
                this.startDate = startDate;
                this.endDate = startDate.clone().addDays(1);
            }

            this.leftCalendar.month.set({ month: this.startDate.getMonth(), year: this.startDate.getFullYear() });
            this.rightCalendar.month.set({ month: this.endDate.getMonth(), year: this.endDate.getFullYear() });
            this.updateCalendars();
        },

        clickApply: function (e) {
            this.hide();
        },

        clickClear: function (e) {
            this.changed = true;
            this.cleared = true;
            this.hide();
        },

        updateCalendars: function () {
            this.leftCalendar.calendar = this.buildCalendar(this.leftCalendar.month.getMonth(), this.leftCalendar.month.getFullYear());
            this.rightCalendar.calendar = this.buildCalendar(this.rightCalendar.month.getMonth(), this.rightCalendar.month.getFullYear());
            this.container.find('.calendar.left').html(this.renderCalendar(this.leftCalendar.calendar, this.startDate, this.minDate, this.maxDate));
            this.container.find('.calendar.right').html(this.renderCalendar(this.rightCalendar.calendar, this.endDate, this.startDate, this.maxDate));
            this.element.trigger('updated',this);
        },

        buildCalendar: function (month, year) {

            var firstDay = Date.create('today').set({ day: 1, month: month, year: year });
            var lastMonth = firstDay.clone().addDays(-1).getMonth();
            var lastYear = firstDay.clone().addDays(-1).getFullYear();

            var daysInMonth = this.getDaysInMonth(year, month);
            var daysInLastMonth = this.getDaysInMonth(lastYear, lastMonth);

            var dayOfWeek = firstDay.getDay();

            //initialize a 6 rows x 7 columns array for the calendar
            var calendar = Array();
            for (var i = 0; i < 6; i++) {
                calendar[i] = Array();
            }

            //populate the calendar with date objects
            var startDay = daysInLastMonth - dayOfWeek + this.locale.firstDay + 1;
            if (startDay > daysInLastMonth)
                startDay -= 7;

            if (dayOfWeek == this.locale.firstDay)
                startDay = daysInLastMonth - 6;

            var curDate = Date.create('today').set({ day: startDay, month: lastMonth, year: lastYear });
            for (var i = 0, col = 0, row = 0; i < 42; i++, col++, curDate = curDate.clone().addDays(1)) {
                if (i > 0 && col % 7 == 0) {
                    col = 0;
                    row++;
                }
                calendar[row][col] = curDate;
            }

            return calendar;

        },

        renderCalendar: function (calendar, selected, minDate, maxDate) {
            var html = '<table class="table-condensed">';
            html += '<thead>';
            html += '<tr>';

            // add empty cell for week number
            if (this.showWeekNumbers)
                html += '<th></th>';

            if (!minDate || minDate < calendar[1][1])
            {
                html += '<th class="prev available"><i class="icon-arrow-left"></i></th>';
            }
            else
            {
                 html += '<th></th>';
            }
            html += '<th colspan="5" style="width: auto">' + this.locale.monthNames[calendar[1][1].getMonth()] + calendar[1][1].format(' {yyyy}') + '</th>';
            if (!maxDate || maxDate > calendar[1][1])
            {
                html += '<th class="next available"><i class="icon-arrow-right"></i></th>';
            }
            else
            {
                 html += '<th></th>';
            }

            html += '</tr>';
            html += '<tr>';

            // add week number label
            if (this.showWeekNumbers)
                html += '<th class="week">' + this.locale.weekLabel + '</th>';

            $.each(this.locale.daysOfWeek, function (index, dayOfWeek) {
                html += '<th>' + dayOfWeek + '</th>';
            });

            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            for (var row = 0; row < 6; row++) {
                html += '<tr>';

                // add week number
                if (this.showWeekNumbers)
                    html += '<td class="week">' + calendar[row][0].getWeek() + '</td>';

                for (var col = 0; col < 7; col++) {
                    var cname = 'available ';
                    cname += (calendar[row][col].getMonth() == calendar[1][1].getMonth()) ? '' : 'off';

                    // Normalise the time so the comparison won't fail
                    selected.setHours(0,0,0,0);

                    if ( (minDate && calendar[row][col] < minDate) || (maxDate && calendar[row][col] > maxDate))
                    {
                        cname = ' off disabled ';
                    }
                    else if (calendar[row][col].is(selected))
                    {
                        cname += ' active ';
                        if (calendar[row][col].is(this.startDate)) { cname += ' start-date '; }
                        if (calendar[row][col].is(this.endDate)) { cname += ' end-date '; }
                    }
                    else if (calendar[row][col] >= this.startDate && calendar[row][col] <= this.endDate)
                    {
                        cname += ' in-range ';
                        if (calendar[row][col].is(this.startDate)) { cname += ' start-date '; }
                        if (calendar[row][col].is(this.endDate)) { cname += ' end-date '; }
                    }

                    var title = 'r' + row + 'c' + col;
                    html += '<td class="' + cname.replace(/\s+/g,' ').replace(/^\s?(.*?)\s?$/,'$1') + '" title="' + title + '">' + calendar[row][col].getDate() + '</td>';
                }
                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';

            return html;

        },

        getDaysInMonth: function (y, m) {
           return /8|3|5|10/.test(--m)?30:m==1?(!(y%4)&&y%100)||!(y%400)?29:28:31;
        }

    };

    $.fn.daterangepicker = function (options, cb) {
      this.each(function() {
        var el = $(this);
        if (!el.data('daterangepicker'))
          el.data('daterangepicker', new DateRangePicker(el, options, cb));
      });
      return this;
    };

} (window.jQuery);

/*
 *  Sugar Library v1.3.7
 *
 *  Freely distributable and licensed under the MIT-style license.
 *  Copyright (c) 2012 Andrew Plummer
 *  http://sugarjs.com/
 *
 * ---------------------------- */
(function(){var k=true,l=null,n=false;function aa(a){return function(){return a}}var p=Object,q=Array,r=RegExp,s=Date,t=String,u=Number,v=Math,ba=typeof global!=="undefined"?global:this,ca=p.defineProperty&&p.defineProperties,x="Array,Boolean,Date,Function,Number,String,RegExp".split(","),da=y(x[0]),ea=y(x[1]),fa=y(x[2]),A=y(x[3]),B=y(x[4]),C=y(x[5]),D=y(x[6]);function y(a){return function(b){return p.prototype.toString.call(b)==="[object "+a+"]"}}
function ga(a){if(!a.SugarMethods){ha(a,"SugarMethods",{});E(a,n,n,{restore:function(){var b=arguments.length===0,c=F(arguments);G(a.SugarMethods,function(d,e){if(b||c.indexOf(d)>-1)ha(e.wa?a.prototype:a,d,e.method)})},extend:function(b,c,d){E(a,d!==n,c,b)}})}}function E(a,b,c,d){var e=b?a.prototype:a,f;ga(a);G(d,function(h,i){f=e[h];if(typeof c==="function")i=ia(e[h],i,c);if(c!==n||!e[h])ha(e,h,i);a.SugarMethods[h]={wa:b,method:i,Da:f}})}
function H(a,b,c,d,e){var f={};d=C(d)?d.split(","):d;d.forEach(function(h,i){e(f,h,i)});E(a,b,c,f)}function ia(a,b,c){return function(){return a&&(c===k||!c.apply(this,arguments))?a.apply(this,arguments):b.apply(this,arguments)}}function ha(a,b,c){if(ca)p.defineProperty(a,b,{value:c,configurable:k,enumerable:n,writable:k});else a[b]=c}function F(a,b){var c=[],d;for(d=0;d<a.length;d++){c.push(a[d]);b&&b.call(a,a[d],d)}return c}
function ja(a,b,c){F(q.prototype.concat.apply([],q.prototype.slice.call(a,c||0)),b)}function ka(a){if(!a||!a.call)throw new TypeError("Callback is not callable");}function I(a){return a!==void 0}function K(a){return a===void 0}function la(a){return a&&typeof a==="object"}function ma(a){return!!a&&p.prototype.toString.call(a)==="[object Object]"&&"hasOwnProperty"in a}function L(a,b){return p.hasOwnProperty.call(a,b)}function G(a,b){for(var c in a)if(L(a,c))if(b.call(a,c,a[c],a)===n)break}
function na(a,b){G(b,function(c){a[c]=b[c]});return a}function oa(a){na(this,a)}oa.prototype.constructor=p;function pa(a,b,c,d){var e=[];a=parseInt(a);for(var f=d<0;!f&&a<=b||f&&a>=b;){e.push(a);c&&c.call(this,a);a+=d||1}return e}function N(a,b,c){c=v[c||"round"];var d=v.pow(10,v.abs(b||0));if(b<0)d=1/d;return c(a*d)/d}function qa(a,b){return N(a,b,"floor")}function O(a,b,c,d){d=v.abs(a).toString(d||10);d=ra(b-d.replace(/\.\d+/,"").length,"0")+d;if(c||a<0)d=(a<0?"-":"+")+d;return d}
function sa(a){if(a>=11&&a<=13)return"th";else switch(a%10){case 1:return"st";case 2:return"nd";case 3:return"rd";default:return"th"}}function ta(){return"\t\n\u000b\u000c\r \u00a0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u2028\u2029\u3000\ufeff"}function ra(a,b){return q(v.max(0,I(a)?a:1)+1).join(b||"")}function ua(a,b){var c=a.toString().match(/[^/]*$/)[0];if(b)c=(c+b).split("").sort().join("").replace(/([gimy])\1+/g,"$1");return c}
function P(a){C(a)||(a=t(a));return a.replace(/([\\/'*+?|()\[\]{}.^$])/g,"\\$1")}function va(a,b){var c=typeof a,d,e,f,h,i,j;if(c==="string")return a;f=p.prototype.toString.call(a);d=ma(a);e=f==="[object Array]";if(a!=l&&d||e){b||(b=[]);if(b.length>1)for(j=b.length;j--;)if(b[j]===a)return"CYC";b.push(a);d=t(a.constructor);h=e?a:p.keys(a).sort();for(j=0;j<h.length;j++){i=e?j:h[j];d+=i+va(a[i],b)}b.pop()}else d=1/a===-Infinity?"-0":t(a&&a.valueOf?a.valueOf():a);return c+f+d}
function wa(a){var b=p.prototype.toString.call(a);return b==="[object Date]"||b==="[object Array]"||b==="[object String]"||b==="[object Number]"||b==="[object RegExp]"||b==="[object Boolean]"||b==="[object Arguments]"||ma(a)}function xa(a,b,c){var d=[],e=a.length,f=b[b.length-1]!==n,h;F(b,function(i){if(ea(i))return n;if(f){i%=e;if(i<0)i=e+i}h=c?a.charAt(i)||"":a[i];d.push(h)});return d.length<2?d[0]:d}
function ya(a,b){H(b,k,n,a,function(c,d){c[d+(d==="equal"?"s":"")]=function(){return p[d].apply(l,[this].concat(F(arguments)))}})}ga(p);G(x,function(a,b){ga(ba[b])});
E(p,n,n,{keys:function(a){var b=[];if(!la(a)&&!D(a)&&!A(a))throw new TypeError("Object required");G(a,function(c){b.push(c)});return b}});
function za(a,b,c,d){var e=a.length,f=d==-1,h=f?e-1:0;c=isNaN(c)?h:parseInt(c>>0);if(c<0)c=e+c;if(!f&&c<0||f&&c>=e)c=h;for(;f&&c>=0||!f&&c<e;){if(a[c]===b)return c;c+=d}return-1}function Aa(a,b,c,d){var e=a.length,f=0,h=I(c);ka(b);if(e==0&&!h)throw new TypeError("Reduce called on empty array with no initial value");else if(h)c=c;else{c=a[d?e-1:f];f++}for(;f<e;){h=d?e-f-1:f;if(h in a)c=b(c,a[h],h,a);f++}return c}
function Ba(a){if(a.length===0)throw new TypeError("First argument must be defined");}E(q,n,n,{isArray:function(a){return da(a)}});
E(q,k,n,{every:function(a,b){var c=this.length,d=0;for(Ba(arguments);d<c;){if(d in this&&!a.call(b,this[d],d,this))return n;d++}return k},some:function(a,b){var c=this.length,d=0;for(Ba(arguments);d<c;){if(d in this&&a.call(b,this[d],d,this))return k;d++}return n},map:function(a,b){var c=this.length,d=0,e=Array(c);for(Ba(arguments);d<c;){if(d in this)e[d]=a.call(b,this[d],d,this);d++}return e},filter:function(a,b){var c=this.length,d=0,e=[];for(Ba(arguments);d<c;){d in this&&a.call(b,this[d],d,this)&&
e.push(this[d]);d++}return e},indexOf:function(a,b){if(C(this))return this.indexOf(a,b);return za(this,a,b,1)},lastIndexOf:function(a,b){if(C(this))return this.lastIndexOf(a,b);return za(this,a,b,-1)},forEach:function(a,b){var c=this.length,d=0;for(ka(a);d<c;){d in this&&a.call(b,this[d],d,this);d++}},reduce:function(a,b){return Aa(this,a,b)},reduceRight:function(a,b){return Aa(this,a,b,k)}});
E(Function,k,n,{bind:function(a){var b=this,c=F(arguments).slice(1),d;if(!A(this))throw new TypeError("Function.prototype.bind called on a non-function");d=function(){return b.apply(b.prototype&&this instanceof b?this:a,c.concat(F(arguments)))};d.prototype=this.prototype;return d}});E(s,n,n,{now:function(){return(new s).getTime()}});
(function(){var a=ta().match(/^\s+$/);try{t.prototype.trim.call([1])}catch(b){a=n}E(t,k,!a,{trim:function(){return this.toString().trimLeft().trimRight()},trimLeft:function(){return this.replace(r("^["+ta()+"]+"),"")},trimRight:function(){return this.replace(r("["+ta()+"]+$"),"")}})})();
(function(){var a=new s(s.UTC(1999,11,31));a=a.toISOString&&a.toISOString()==="1999-12-31T00:00:00.000Z";H(s,k,!a,"toISOString,toJSON",function(b,c){b[c]=function(){return O(this.getUTCFullYear(),4)+"-"+O(this.getUTCMonth()+1,2)+"-"+O(this.getUTCDate(),2)+"T"+O(this.getUTCHours(),2)+":"+O(this.getUTCMinutes(),2)+":"+O(this.getUTCSeconds(),2)+"."+O(this.getUTCMilliseconds(),3)+"Z"}})})();
function Ca(a,b,c,d){var e=k;if(a===b)return k;else if(D(b)&&C(a))return r(b).test(a);else if(A(b))return b.apply(c,d);else if(ma(b)&&la(a)){G(b,function(f){Ca(a[f],b[f],c,[a[f],a])||(e=n)});return e}else return wa(a)&&wa(b)?va(a)===va(b):a===b}function R(a,b,c,d){return K(b)?a:A(b)?b.apply(c,d||[]):A(a[b])?a[b].call(a):a[b]}
function S(a,b,c,d){var e,f;if(c<0)c=a.length+c;f=isNaN(c)?0:c;for(c=d===k?a.length+f:a.length;f<c;){e=f%a.length;if(e in a){if(b.call(a,a[e],e,a)===n)break}else return Ea(a,b,f,d);f++}}function Ea(a,b,c){var d=[],e;for(e in a)e in a&&e>>>0==e&&e!=4294967295&&e>=c&&d.push(parseInt(e));d.sort().each(function(f){return b.call(a,a[f],f,a)});return a}function Fa(a,b,c,d,e){var f,h;S(a,function(i,j,g){if(Ca(i,b,g,[i,j,g])){f=i;h=j;return n}},c,d);return e?h:f}
function Ga(a,b){var c=[],d={},e;S(a,function(f,h){e=b?R(f,b,a,[f,h,a]):f;Ha(d,e)||c.push(f)});return c}function Ia(a,b,c){var d=[],e={};b.each(function(f){Ha(e,f)});a.each(function(f){var h=va(f),i=!wa(f);if(Ja(e,h,f,i)!=c){var j=0;if(i)for(h=e[h];j<h.length;)if(h[j]===f)h.splice(j,1);else j+=1;else delete e[h];d.push(f)}});return d}function Ka(a,b,c){b=b||Infinity;c=c||0;var d=[];S(a,function(e){if(da(e)&&c<b)d=d.concat(Ka(e,b,c+1));else d.push(e)});return d}
function La(a){var b=[];F(a,function(c){b=b.concat(c)});return b}function Ja(a,b,c,d){var e=b in a;if(d){a[b]||(a[b]=[]);e=a[b].indexOf(c)!==-1}return e}function Ha(a,b){var c=va(b),d=!wa(b),e=Ja(a,c,b,d);if(d)a[c].push(b);else a[c]=b;return e}
function Ma(a,b,c,d){var e,f=[],h=c==="max",i=c==="min",j=Array.isArray(a);G(a,function(g){var m=a[g];g=R(m,b,a,j?[m,parseInt(g),a]:[]);if(K(g))throw new TypeError("Cannot compare with undefined");if(g===e)f.push(m);else if(K(e)||h&&g>e||i&&g<e){f=[m];e=g}});j||(f=Ka(f,1));return d?f:f[0]}function Na(a){if(q[Oa])a=a.toLowerCase();return a.replace(q[Pa],"")}function Qa(a,b){var c=a.charAt(b);return(q[Ra]||{})[c]||c}function Sa(a){var b=q[Ta];return a?b.indexOf(a):l}
var Ta="AlphanumericSortOrder",Pa="AlphanumericSortIgnore",Oa="AlphanumericSortIgnoreCase",Ra="AlphanumericSortEquivalents";E(q,n,n,{create:function(){var a=[],b;F(arguments,function(c){if(la(c))try{b=q.prototype.slice.call(c,0);if(b.length>0)c=b}catch(d){}a=a.concat(c)});return a}});
E(q,k,n,{find:function(a,b,c){return Fa(this,a,b,c)},findAll:function(a,b,c){var d=[];S(this,function(e,f,h){Ca(e,a,h,[e,f,h])&&d.push(e)},b,c);return d},findIndex:function(a,b,c){a=Fa(this,a,b,c,k);return K(a)?-1:a},count:function(a){if(K(a))return this.length;return this.findAll(a).length},removeAt:function(a,b){if(K(a))return this;if(K(b))b=a;for(var c=0;c<=b-a;c++)this.splice(a,1);return this},include:function(a,b){return this.clone().add(a,b)},exclude:function(){return q.prototype.remove.apply(this.clone(),
arguments)},clone:function(){return na([],this)},unique:function(a){return Ga(this,a)},flatten:function(a){return Ka(this,a)},union:function(){return Ga(this.concat(La(arguments)))},intersect:function(){return Ia(this,La(arguments),n)},subtract:function(){return Ia(this,La(arguments),k)},at:function(){return xa(this,arguments)},first:function(a){if(K(a))return this[0];if(a<0)a=0;return this.slice(0,a)},last:function(a){if(K(a))return this[this.length-1];return this.slice(this.length-a<0?0:this.length-
a)},from:function(a){return this.slice(a)},to:function(a){if(K(a))a=this.length;return this.slice(0,a)},min:function(a,b){return Ma(this,a,"min",b)},max:function(a,b){return Ma(this,a,"max",b)},least:function(a,b){return Ma(this.groupBy.apply(this,[a]),"length","min",b)},most:function(a,b){return Ma(this.groupBy.apply(this,[a]),"length","max",b)},sum:function(a){a=a?this.map(a):this;return a.length>0?a.reduce(function(b,c){return b+c}):0},average:function(a){a=a?this.map(a):this;return a.length>0?
a.sum()/a.length:0},inGroups:function(a,b){var c=arguments.length>1,d=this,e=[],f=N(this.length/a,void 0,"ceil");pa(0,a-1,function(h){h=h*f;var i=d.slice(h,h+f);c&&i.length<f&&pa(1,f-i.length,function(){i=i.add(b)});e.push(i)});return e},inGroupsOf:function(a,b){var c=[],d=this.length,e=this,f;if(d===0||a===0)return e;if(K(a))a=1;if(K(b))b=l;pa(0,N(d/a,void 0,"ceil")-1,function(h){for(f=e.slice(a*h,a*h+a);f.length<a;)f.push(b);c.push(f)});return c},isEmpty:function(){return this.compact().length==
0},sortBy:function(a,b){var c=this.clone();c.sort(function(d,e){var f,h;f=R(d,a,c,[d]);h=R(e,a,c,[e]);if(C(f)&&C(h)){f=f;h=h;var i,j,g,m,o=0,w=0;f=Na(f);h=Na(h);do{g=Qa(f,o);m=Qa(h,o);i=Sa(g);j=Sa(m);if(i===-1||j===-1){i=f.charCodeAt(o)||l;j=h.charCodeAt(o)||l}g=g!==f.charAt(o);m=m!==h.charAt(o);if(g!==m&&w===0)w=g-m;o+=1}while(i!=l&&j!=l&&i===j);f=i===j?w:i<j?-1:1}else f=f<h?-1:f>h?1:0;return f*(b?-1:1)});return c},randomize:function(){for(var a=this.concat(),b,c,d=a.length;d;b=parseInt(v.random()*
d),c=a[--d],a[d]=a[b],a[b]=c);return a},zip:function(){var a=F(arguments);return this.map(function(b,c){return[b].concat(a.map(function(d){return c in d?d[c]:l}))})},sample:function(a){var b=this.randomize();return arguments.length>0?b.slice(0,a):b[0]},each:function(a,b,c){S(this,a,b,c);return this},add:function(a,b){if(!B(u(b))||isNaN(b))b=this.length;q.prototype.splice.apply(this,[b,0].concat(a));return this},remove:function(){var a,b=this;F(arguments,function(c){for(a=0;a<b.length;)if(Ca(b[a],
c,b,[b[a],a,b]))b.splice(a,1);else a++});return b},compact:function(a){var b=[];S(this,function(c){if(da(c))b.push(c.compact());else if(a&&c)b.push(c);else!a&&c!=l&&c.valueOf()===c.valueOf()&&b.push(c)});return b},groupBy:function(a,b){var c=this,d={},e;S(c,function(f,h){e=R(f,a,c,[f,h,c]);d[e]||(d[e]=[]);d[e].push(f)});b&&G(d,b);return d},none:function(){return!this.any.apply(this,arguments)}});E(q,k,n,{all:q.prototype.every,any:q.prototype.some,insert:q.prototype.add});
function Ua(a){if(a&&a.valueOf)a=a.valueOf();return p.keys(a)}function Va(a,b){H(p,n,n,a,function(c,d){c[d]=function(e,f,h){var i=Ua(e);h=q.prototype[d].call(i,function(j){return b?R(e[j],f,e,[j,e[j],e]):Ca(e[j],f,e,[j,e[j],e])},h);if(da(h))h=h.reduce(function(j,g){j[g]=e[g];return j},{});return h}});ya(a,oa)}
E(p,n,n,{map:function(a,b){return Ua(a).reduce(function(c,d){c[d]=R(a[d],b,a,[d,a[d],a]);return c},{})},reduce:function(a){var b=Ua(a).map(function(c){return a[c]});return b.reduce.apply(b,F(arguments).slice(1))},each:function(a,b){ka(b);G(a,b);return a},size:function(a){return Ua(a).length}});var Wa="any,all,none,count,find,findAll,isEmpty".split(","),Xa="sum,average,min,max,least,most".split(","),Ya="map,reduce,size".split(","),Za=Wa.concat(Xa).concat(Ya);
(function(){H(q,k,function(){var a=arguments;return a.length>0&&!A(a[0])},"map,every,all,some,any,none,filter",function(a,b){a[b]=function(c){return this[b](function(d,e){return b==="map"?R(d,c,this,[d,e,this]):Ca(d,c,this,[d,e,this])})}})})();
(function(){q[Ta]="A\u00c1\u00c0\u00c2\u00c3\u0104BC\u0106\u010c\u00c7D\u010e\u00d0E\u00c9\u00c8\u011a\u00ca\u00cb\u0118FG\u011eH\u0131I\u00cd\u00cc\u0130\u00ce\u00cfJKL\u0141MN\u0143\u0147\u00d1O\u00d3\u00d2\u00d4PQR\u0158S\u015a\u0160\u015eT\u0164U\u00da\u00d9\u016e\u00db\u00dcVWXY\u00ddZ\u0179\u017b\u017d\u00de\u00c6\u0152\u00d8\u00d5\u00c5\u00c4\u00d6".split("").map(function(b){return b+b.toLowerCase()}).join("");var a={};S("A\u00c1\u00c0\u00c2\u00c3\u00c4,C\u00c7,E\u00c9\u00c8\u00ca\u00cb,I\u00cd\u00cc\u0130\u00ce\u00cf,O\u00d3\u00d2\u00d4\u00d5\u00d6,S\u00df,U\u00da\u00d9\u00db\u00dc".split(","),
function(b){var c=b.charAt(0);S(b.slice(1).split(""),function(d){a[d]=c;a[d.toLowerCase()]=c.toLowerCase()})});q[Oa]=k;q[Ra]=a})();Va(Wa);Va(Xa,k);ya(Ya,oa);
var T,$a,ab=["ampm","hour","minute","second","ampm","utc","offset_sign","offset_hours","offset_minutes","ampm"],bb="({t})?\\s*(\\d{1,2}(?:[,.]\\d+)?)(?:{h}([0-5]\\d(?:[,.]\\d+)?)?{m}(?::?([0-5]\\d(?:[,.]\\d+)?){s})?\\s*(?:({t})|(Z)|(?:([+-])(\\d{2,2})(?::?(\\d{2,2}))?)?)?|\\s*({t}))",cb={},db,eb,fb,gb=[],hb=[{ba:"f{1,4}|ms|milliseconds",format:function(a){return V(a,"Milliseconds")}},{ba:"ss?|seconds",format:function(a){return V(a,"Seconds")}},{ba:"mm?|minutes",format:function(a){return V(a,"Minutes")}},
{ba:"hh?|hours|12hr",format:function(a){a=V(a,"Hours");return a===0?12:a-qa(a/13)*12}},{ba:"HH?|24hr",format:function(a){return V(a,"Hours")}},{ba:"dd?|date|day",format:function(a){return V(a,"Date")}},{ba:"dow|weekday",la:k,format:function(a,b,c){a=V(a,"Day");return b.weekdays[a+(c-1)*7]}},{ba:"MM?",format:function(a){return V(a,"Month")+1}},{ba:"mon|month",la:k,format:function(a,b,c){a=V(a,"Month");return b.months[a+(c-1)*12]}},{ba:"y{2,4}|year",format:function(a){return V(a,"FullYear")}},{ba:"[Tt]{1,2}",
format:function(a,b,c,d){if(b.ampm.length==0)return"";a=V(a,"Hours");b=b.ampm[qa(a/12)];if(d.length===1)b=b.slice(0,1);if(d.slice(0,1)==="T")b=b.toUpperCase();return b}},{ba:"z{1,4}|tz|timezone",text:k,format:function(a,b,c,d){a=a.getUTCOffset();if(d=="z"||d=="zz")a=a.replace(/(\d{2})(\d{2})/,function(e,f){return O(f,d.length)});return a}},{ba:"iso(tz|timezone)",format:function(a){return a.getUTCOffset(k)}},{ba:"ord",format:function(a){a=V(a,"Date");return a+sa(a)}}],ib=[{$:"year",method:"FullYear",
ja:k,da:function(a){return(365+(a?a.isLeapYear()?1:0:0.25))*24*60*60*1E3}},{$:"month",method:"Month",ja:k,da:function(a,b){var c=30.4375,d;if(a){d=a.daysInMonth();if(b<=d.days())c=d}return c*24*60*60*1E3},error:0.919},{$:"week",method:"Week",da:aa(6048E5)},{$:"day",method:"Date",ja:k,da:aa(864E5)},{$:"hour",method:"Hours",da:aa(36E5)},{$:"minute",method:"Minutes",da:aa(6E4)},{$:"second",method:"Seconds",da:aa(1E3)},{$:"millisecond",method:"Milliseconds",da:aa(1)}],jb={};
function kb(a){na(this,a);this.ga=gb.concat()}
kb.prototype={getMonth:function(a){return B(a)?a-1:this.months.indexOf(a)%12},getWeekday:function(a){return this.weekdays.indexOf(a)%7},oa:function(a){var b;return B(a)?a:a&&(b=this.numbers.indexOf(a))!==-1?(b+1)%10:1},ta:function(a){var b=this;return a.replace(r(this.num,"g"),function(c){return b.oa(c)||""})},ra:function(a){return T.units[this.units.indexOf(a)%8]},ua:function(a){return this.na(a,a[2]>0?"future":"past")},qa:function(a){return this.na(lb(a),"duration")},va:function(a){a=a||this.code;
return a==="en"||a==="en-US"?k:this.variant},ya:function(a){return a===this.ampm[0]},za:function(a){return a&&a===this.ampm[1]},na:function(a,b){var c,d,e=a[0],f=a[1],h=a[2],i=this[b]||this.relative;if(A(i))return i.call(this,e,f,h,b);d=this.units[(this.plural&&e>1?1:0)*8+f]||this.units[f];if(this.capitalizeUnit)d=mb(d);c=this.modifiers.filter(function(j){return j.name=="sign"&&j.value==(h>0?1:-1)})[0];return i.replace(/\{(.*?)\}/g,function(j,g){switch(g){case "num":return e;case "unit":return d;
case "sign":return c.src}})},sa:function(){return this.ma?[this.ma].concat(this.ga):this.ga},addFormat:function(a,b,c,d,e){var f=c||[],h=this,i;a=a.replace(/\s+/g,"[-,. ]*");a=a.replace(/\{([^,]+?)\}/g,function(j,g){var m,o,w,z=g.match(/\?$/);w=g.match(/^(\d+)\??$/);var J=g.match(/(\d)(?:-(\d))?/),M=g.replace(/[^a-z]+$/,"");if(w)m=h.tokens[w[1]];else if(h[M])m=h[M];else if(h[M+"s"]){m=h[M+"s"];if(J){o=[];m.forEach(function(Q,Da){var U=Da%(h.units?8:m.length);if(U>=J[1]&&U<=(J[2]||J[1]))o.push(Q)});
m=o}m=nb(m)}if(w)w="(?:"+m+")";else{c||f.push(M);w="("+m+")"}if(z)w+="?";return w});if(b){b=ob(bb,h,e);e=["t","[\\s\\u3000]"].concat(h.timeMarker);i=a.match(/\\d\{\d,\d\}\)+\??$/);pb(h,"(?:"+b+")[,\\s\\u3000]+?"+a,ab.concat(f),d);pb(h,a+"(?:[,\\s]*(?:"+e.join("|")+(i?"+":"*")+")"+b+")?",f.concat(ab),d)}else pb(h,a,f,d)}};function qb(a,b){var c;C(a)||(a="");c=jb[a]||jb[a.slice(0,2)];if(b===n&&!c)throw Error("Invalid locale.");return c||$a}
function rb(a,b){function c(g){var m=i[g];if(C(m))i[g]=m.split(",");else m||(i[g]=[])}function d(g,m){g=g.split("+").map(function(o){return o.replace(/(.+):(.+)$/,function(w,z,J){return J.split("|").map(function(M){return z+M}).join("|")})}).join("|");return g.split("|").forEach(m)}function e(g,m,o){var w=[];i[g].forEach(function(z,J){if(m)z+="+"+z.slice(0,3);d(z,function(M,Q){w[Q*o+J]=M.toLowerCase()})});i[g]=w}function f(g,m,o){g="\\d{"+g+","+m+"}";if(o)g+="|(?:"+nb(i.numbers)+")+";return g}function h(g,
m){i[g]=i[g]||m}var i,j;i=new kb(b);c("modifiers");"months,weekdays,units,numbers,articles,tokens,timeMarker,ampm,timeSuffixes,dateParse,timeParse".split(",").forEach(c);j=!i.monthSuffix;e("months",j,12);e("weekdays",j,7);e("units",n,8);e("numbers",n,10);h("code",a);h("date",f(1,2,i.digitDate));h("year","'\\d{2}|"+f(4,4));h("num",function(){var g=["\\d+"].concat(i.articles);if(i.numbers)g=g.concat(i.numbers);return nb(g)}());(function(){var g=[];i.ha={};i.modifiers.forEach(function(m){var o=m.name;
d(m.src,function(w){var z=i[o];i.ha[w]=m;g.push({name:o,src:w,value:m.value});i[o]=z?z+"|"+w:w})});i.day+="|"+nb(i.weekdays);i.modifiers=g})();if(i.monthSuffix){i.month=f(1,2);i.months=pa(1,12).map(function(g){return g+i.monthSuffix})}i.full_month=f(1,2)+"|"+nb(i.months);i.timeSuffixes.length>0&&i.addFormat(ob(bb,i),n,ab);i.addFormat("{day}",k);i.addFormat("{month}"+(i.monthSuffix||""));i.addFormat("{year}"+(i.yearSuffix||""));i.timeParse.forEach(function(g){i.addFormat(g,k)});i.dateParse.forEach(function(g){i.addFormat(g)});
return jb[a]=i}function pb(a,b,c,d){a.ga.unshift({Ba:d,xa:a,Aa:r("^"+b+"$","i"),to:c})}function mb(a){return a.slice(0,1).toUpperCase()+a.slice(1)}function nb(a){return a.filter(function(b){return!!b}).join("|")}function sb(a,b){var c;if(ma(a[0]))return a;else if(B(a[0])&&!B(a[1]))return[a[0]];else if(C(a[0])&&b)return[tb(a[0]),a[1]];c={};eb.forEach(function(d,e){c[d.$]=a[e]});return[c]}
function tb(a,b){var c={};if(match=a.match(/^(\d+)?\s?(\w+?)s?$/i)){if(K(b))b=parseInt(match[1])||1;c[match[2].toLowerCase()]=b}return c}function ub(a,b){var c={},d,e;b.forEach(function(f,h){d=a[h+1];if(!(K(d)||d==="")){if(f==="year")c.Ca=d.replace(/'/,"");e=parseFloat(d.replace(/'/,"").replace(/,/,"."));c[f]=!isNaN(e)?e:d.toLowerCase()}});return c}function vb(a){a=a.trim().replace(/^(just )?now|\.+$/i,"");return wb(a)}
function wb(a){return a.replace(db,function(b,c,d){var e=0,f=1,h,i;if(c)return b;d.split("").reverse().forEach(function(j){j=cb[j];var g=j>9;if(g){if(h)e+=f;f*=j/(i||1);i=j}else{if(h===n)f*=10;e+=f*j}h=g});if(h)e+=f;return e})}
function xb(a,b,c,d){var e=new s,f=n,h,i,j,g,m,o,w,z,J;e.utc(d);if(fa(a))e.utc(a.isUTC()).setTime(a.getTime());else if(B(a))e.setTime(a);else if(ma(a)){e.set(a,k);g=a}else if(C(a)){h=qb(b);a=vb(a);h&&G(h.sa(),function(M,Q){var Da=a.match(Q.Aa);if(Da){j=Q;i=j.xa;g=ub(Da,j.to,i);g.utc&&e.utc();i.ma=j;if(g.timestamp){g=g.timestamp;return n}if(j.Ba&&!C(g.month)&&(C(g.date)||h.va(b))){z=g.month;g.month=g.date;g.date=z}if(g.year&&g.Ca.length===2)g.year=N(V(new s,"FullYear")/100)*100-N(g.year/100)*100+g.year;
if(g.month){g.month=i.getMonth(g.month);if(g.shift&&!g.unit)g.unit=i.units[7]}if(g.weekday&&g.date)delete g.weekday;else if(g.weekday){g.weekday=i.getWeekday(g.weekday);if(g.shift&&!g.unit)g.unit=i.units[5]}if(g.day&&(z=i.ha[g.day])){g.day=z.value;e.reset();f=k}else if(g.day&&(o=i.getWeekday(g.day))>-1){delete g.day;if(g.num&&g.month){J=function(){var U=e.getWeekday();e.setWeekday(7*(g.num-1)+(U>o?o+7:o))};g.day=1}else g.weekday=o}if(g.date&&!B(g.date))g.date=i.ta(g.date);if(i.za(g.ampm)&&g.hour<
12)g.hour+=12;else if(i.ya(g.ampm)&&g.hour===12)g.hour=0;if("offset_hours"in g||"offset_minutes"in g){e.utc();g.offset_minutes=g.offset_minutes||0;g.offset_minutes+=g.offset_hours*60;if(g.offset_sign==="-")g.offset_minutes*=-1;g.minute-=g.offset_minutes}if(g.unit){f=k;w=i.oa(g.num);m=i.ra(g.unit);if(g.shift||g.edge){w*=(z=i.ha[g.shift])?z.value:0;if(m==="month"&&I(g.date)){e.set({day:g.date},k);delete g.date}if(m==="year"&&I(g.month)){e.set({month:g.month,day:g.date},k);delete g.month;delete g.date}}if(g.sign&&
(z=i.ha[g.sign]))w*=z.value;if(I(g.weekday)){e.set({weekday:g.weekday},k);delete g.weekday}g[m]=(g[m]||0)+w}if(g.year_sign==="-")g.year*=-1;fb.slice(1,4).forEach(function(U,$b){var Eb=g[U.$],Fb=Eb%1;if(Fb){g[fb[$b].$]=N(Fb*(U.$==="second"?1E3:60));g[U.$]=qa(Eb)}});return n}});if(j)if(f)e.advance(g);else{e._utc&&e.reset();yb(e,g,k,n,c)}else{e=a?new s(a):new s;d&&e.addMinutes(e.getTimezoneOffset())}if(g&&g.edge){z=i.ha[g.edge];G(fb.slice(4),function(M,Q){if(I(g[Q.$])){m=Q.$;return n}});if(m==="year")g.fa=
"month";else if(m==="month"||m==="week")g.fa="day";e[(z.value<0?"endOf":"beginningOf")+mb(m)]();z.value===-2&&e.reset()}J&&J();e.utc(n)}return{ea:e,set:g}}function lb(a){var b,c=v.abs(a),d=c,e=0;fb.slice(1).forEach(function(f,h){b=qa(N(c/f.da()*10)/10);if(b>=1){d=b;e=h+1}});return[d,e,a]}
function zb(a,b,c,d){var e,f=qb(d),h=r(/^[A-Z]/);if(a.isValid())if(Date[b])b=Date[b];else{if(A(b)){e=lb(a.millisecondsFromNow());b=b.apply(a,e.concat(f))}}else return"Invalid Date";if(!b&&c){e=e||lb(a.millisecondsFromNow());if(e[1]===0){e[1]=1;e[0]=1}return f.ua(e)}b=b||"long";b=f[b]||b;hb.forEach(function(i){b=b.replace(r("\\{("+i.ba+")(\\d)?\\}",i.la?"i":""),function(j,g,m){j=i.format(a,f,m||1,g);m=g.length;var o=g.match(/^(.)\1+$/);if(i.la){if(m===3)j=j.slice(0,3);if(o||g.match(h))j=mb(j)}else if(o&&
!i.text)j=(B(j)?O(j,m):j.toString()).slice(-m);return j})});return b}
function Ab(a,b,c,d){var e,f,h,i=0,j=0,g=0;e=xb(b,l,l,d);if(c>0){j=g=c;f=k}if(!e.ea.isValid())return n;if(e.set&&e.set.fa){ib.forEach(function(m){if(m.$===e.set.fa)i=m.da(e.ea,a-e.ea)-1});b=mb(e.set.fa);if(e.set.edge||e.set.shift)e.ea["beginningOf"+b]();if(e.set.fa==="month")h=e.ea.clone()["endOf"+b]().getTime();if(!f&&e.set.sign&&e.set.fa!="millisecond"){j=50;g=-50}}f=a.getTime();b=e.ea.getTime();h=h||b+i;h=Bb(a,b,h);return f>=b-j&&f<=h+g}
function Bb(a,b,c){b=new Date(b);a=(new Date(c)).utc(a.isUTC());if(V(a,"Hours")!==23){b=b.getTimezoneOffset();a=a.getTimezoneOffset();if(b!==a)c+=(a-b).minutes()}return c}
function yb(a,b,c,d,e){function f(g){return I(b[g])?b[g]:b[g+"s"]}function h(g){return I(f(g))}var i,j;if(B(b)&&d)b={milliseconds:b};else if(B(b)){a.setTime(b);return a}if(I(b.date))b.day=b.date;G(fb,function(g,m){var o=m.$==="day";if(h(m.$)||o&&h("weekday")){b.fa=m.$;j=+g;return n}else if(c&&m.$!=="week"&&(!o||!h("week")))W(a,m.method,o?1:0)});ib.forEach(function(g){var m=g.$;g=g.method;var o;o=f(m);if(!K(o)){if(d){if(m==="week"){o=(b.day||0)+o*7;g="Date"}o=o*d+V(a,g)}else m==="month"&&h("day")&&
W(a,"Date",15);W(a,g,o);if(d&&m==="month"){m=o;if(m<0)m=m%12+12;m%12!=V(a,"Month")&&W(a,"Date",0)}}});if(!d&&!h("day")&&h("weekday")){i=f("weekday");a.setWeekday(i)}(function(){var g=new s;return e===-1&&a>g||e===1&&a<g})()&&G(fb.slice(j+1),function(g,m){if((m.ja||m.$==="week"&&h("weekday"))&&!(h(m.$)||m.$==="day"&&h("weekday"))){a[m.ia](e);return n}});return a}function V(a,b){return a["get"+(a._utc?"UTC":"")+b]()}function W(a,b,c){return a["set"+(a._utc?"UTC":"")+b](c)}
function ob(a,b,c){var d={h:0,m:1,s:2},e;b=b||T;return a.replace(/{([a-z])}/g,function(f,h){var i=[],j=h==="h",g=j&&!c;if(h==="t")return b.ampm.join("|");else{j&&i.push(":");if(e=b.timeSuffixes[d[h]])i.push(e+"\\s*");return i.length===0?"":"(?:"+i.join("|")+")"+(g?"":"?")}})}function X(a,b,c){var d,e;if(B(a[1]))d=sb(a)[0];else{d=a[0];e=a[1]}return xb(d,e,b,c).ea}
s.extend({create:function(){return X(arguments)},past:function(){return X(arguments,-1)},future:function(){return X(arguments,1)},addLocale:function(a,b){return rb(a,b)},setLocale:function(a){var b=qb(a,n);$a=b;if(a&&a!=b.code)b.code=a;return b},getLocale:function(a){return!a?$a:qb(a,n)},addFormat:function(a,b,c){pb(qb(c),a,b)}},n,n);
s.extend({set:function(){var a=sb(arguments);return yb(this,a[0],a[1])},setWeekday:function(a){if(!K(a))return W(this,"Date",V(this,"Date")+a-V(this,"Day"))},setWeek:function(a){if(!K(a)){V(this,"Date");W(this,"Month",0);W(this,"Date",a*7+1);return this.getTime()}},getWeek:function(){var a=this;a=a.clone();var b=V(a,"Day")||7;a.addDays(4-b).reset();return 1+qa(a.daysSince(a.clone().beginningOfYear())/7)},getUTCOffset:function(a){var b=this._utc?0:this.getTimezoneOffset(),c=a===k?":":"";if(!b&&a)return"Z";
return O(N(-b/60),2,k)+c+O(b%60,2)},utc:function(a){ha(this,"_utc",a===k||arguments.length===0);return this},isUTC:function(){return!!this._utc||this.getTimezoneOffset()===0},advance:function(){var a=sb(arguments,k);return yb(this,a[0],a[1],1)},rewind:function(){var a=sb(arguments,k);return yb(this,a[0],a[1],-1)},isValid:function(){return!isNaN(this.getTime())},isAfter:function(a,b){return this.getTime()>s.create(a).getTime()-(b||0)},isBefore:function(a,b){return this.getTime()<s.create(a).getTime()+
(b||0)},isBetween:function(a,b,c){var d=this.getTime();a=s.create(a).getTime();var e=s.create(b).getTime();b=v.min(a,e);a=v.max(a,e);c=c||0;return b-c<d&&a+c>d},isLeapYear:function(){var a=V(this,"FullYear");return a%4===0&&a%100!==0||a%400===0},daysInMonth:function(){return 32-V(new s(V(this,"FullYear"),V(this,"Month"),32),"Date")},format:function(a,b){return zb(this,a,n,b)},relative:function(a,b){if(C(a)){b=a;a=l}return zb(this,a,k,b)},is:function(a,b,c){var d,e;if(this.isValid()){if(C(a)){a=a.trim().toLowerCase();
e=this.clone().utc(c);switch(k){case a==="future":return this.getTime()>(new s).getTime();case a==="past":return this.getTime()<(new s).getTime();case a==="weekday":return V(e,"Day")>0&&V(e,"Day")<6;case a==="weekend":return V(e,"Day")===0||V(e,"Day")===6;case (d=T.weekdays.indexOf(a)%7)>-1:return V(e,"Day")===d;case (d=T.months.indexOf(a)%12)>-1:return V(e,"Month")===d}}return Ab(this,a,b,c)}},reset:function(a){var b={},c;a=a||"hours";if(a==="date")a="days";c=ib.some(function(d){return a===d.$||
a===d.$+"s"});b[a]=a.match(/^days?/)?1:0;return c?this.set(b,k):this},clone:function(){var a=new s(this.getTime());a.utc(this.isUTC());return a}});s.extend({iso:function(){return this.toISOString()},getWeekday:s.prototype.getDay,getUTCWeekday:s.prototype.getUTCDay});
function Cb(a,b){function c(){return N(this*b)}function d(){return X(arguments)[a.ia](this)}function e(){return X(arguments)[a.ia](-this)}var f=a.$,h={};h[f]=c;h[f+"s"]=c;h[f+"Before"]=e;h[f+"sBefore"]=e;h[f+"Ago"]=e;h[f+"sAgo"]=e;h[f+"After"]=d;h[f+"sAfter"]=d;h[f+"FromNow"]=d;h[f+"sFromNow"]=d;u.extend(h)}u.extend({duration:function(a){return qb(a).qa(this)}});
T=$a=s.addLocale("en",{plural:k,timeMarker:"at",ampm:"am,pm",months:"January,February,March,April,May,June,July,August,September,October,November,December",weekdays:"Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday",units:"millisecond:|s,second:|s,minute:|s,hour:|s,day:|s,week:|s,month:|s,year:|s",numbers:"one,two,three,four,five,six,seven,eight,nine,ten",articles:"a,an,the",tokens:"the,st|nd|rd|th,of","short":"{Month} {d}, {yyyy}","long":"{Month} {d}, {yyyy} {h}:{mm}{tt}",full:"{Weekday} {Month} {d}, {yyyy} {h}:{mm}:{ss}{tt}",
past:"{num} {unit} {sign}",future:"{num} {unit} {sign}",duration:"{num} {unit}",modifiers:[{name:"day",src:"yesterday",value:-1},{name:"day",src:"today",value:0},{name:"day",src:"tomorrow",value:1},{name:"sign",src:"ago|before",value:-1},{name:"sign",src:"from now|after|from|in|later",value:1},{name:"edge",src:"last day",value:-2},{name:"edge",src:"end",value:-1},{name:"edge",src:"first day|beginning",value:1},{name:"shift",src:"last",value:-1},{name:"shift",src:"the|this",value:0},{name:"shift",
src:"next",value:1}],dateParse:["{num} {unit} {sign}","{sign} {num} {unit}","{month} {year}","{shift} {unit=5-7}","{0?} {date}{1}","{0?} {edge} of {shift?} {unit=4-7?}{month?}{year?}"],timeParse:["{0} {num}{1} {day} of {month} {year?}","{weekday?} {month} {date}{1?} {year?}","{date} {month} {year}","{date} {month}","{shift} {weekday}","{shift} week {weekday}","{weekday} {2?} {shift} week","{num} {unit=4-5} {sign} {day}","{0?} {date}{1} of {month}","{0?}{month?} {date?}{1?} of {shift} {unit=6-7}"]});
fb=ib.concat().reverse();eb=ib.concat();eb.splice(2,1);
H(s,k,n,ib,function(a,b,c){function d(g){g=g/h;var m=g%1,o=b.error||0.999;if(m&&v.abs(m%1)>o)g=N(g);return parseInt(g)}var e=b.$,f=mb(e),h=b.da(),i,j;b.ia="add"+f+"s";i=function(g,m){return d(this.getTime()-s.create(g,m).getTime())};j=function(g,m){return d(s.create(g,m).getTime()-this.getTime())};a[e+"sAgo"]=j;a[e+"sUntil"]=j;a[e+"sSince"]=i;a[e+"sFromNow"]=i;a[b.ia]=function(g,m){var o={};o[e]=g;return this.advance(o,m)};Cb(b,h);c<3&&["Last","This","Next"].forEach(function(g){a["is"+g+f]=function(){return this.is(g+
" "+e)}});if(c<4){a["beginningOf"+f]=function(){var g={};switch(e){case "year":g.year=V(this,"FullYear");break;case "month":g.month=V(this,"Month");break;case "day":g.day=V(this,"Date");break;case "week":g.weekday=0}return this.set(g,k)};a["endOf"+f]=function(){var g={hours:23,minutes:59,seconds:59,milliseconds:999};switch(e){case "year":g.month=11;g.day=31;break;case "month":g.day=this.daysInMonth();break;case "week":g.weekday=6}return this.set(g,k)}}});
T.addFormat("([+-])?(\\d{4,4})[-.]?{full_month}[-.]?(\\d{1,2})?",k,["year_sign","year","month","date"],n,k);T.addFormat("(\\d{1,2})[-.\\/]{full_month}(?:[-.\\/](\\d{2,4}))?",k,["date","month","year"],k);T.addFormat("{full_month}[-.](\\d{4,4})",n,["month","year"]);T.addFormat("\\/Date\\((\\d+(?:\\+\\d{4,4})?)\\)\\/",n,["timestamp"]);T.addFormat(ob(bb,T),n,ab);gb=T.ga.slice(0,7).reverse();T.ga=T.ga.slice(7).concat(gb);H(s,k,n,"short,long,full",function(a,b){a[b]=function(c){return zb(this,b,n,c)}});
"\u3007\u4e00\u4e8c\u4e09\u56db\u4e94\u516d\u4e03\u516b\u4e5d\u5341\u767e\u5343\u4e07".split("").forEach(function(a,b){if(b>9)b=v.pow(10,b-9);cb[a]=b});"\uff10\uff11\uff12\uff13\uff14\uff15\uff16\uff17\uff18\uff19".split("").forEach(function(a,b){cb[a]=b});db=r("([\u671f\u9031\u5468])?([\u3007\u4e00\u4e8c\u4e09\u56db\u4e94\u516d\u4e03\u516b\u4e5d\u5341\u767e\u5343\u4e07\uff10\uff11\uff12\uff13\uff14\uff15\uff16\uff17\uff18\uff19]+)(?!\u6628)","g");
(function(){var a="today,yesterday,tomorrow,weekday,weekend,future,past".split(","),b=T.weekdays.slice(0,7),c=T.months.slice(0,12);H(s,k,n,a.concat(b).concat(c),function(d,e){d["is"+mb(e)]=function(f){return this.is(e,0,f)}})})();(function(){s.extend({utc:{create:function(){return X(arguments,0,k)},past:function(){return X(arguments,-1,k)},future:function(){return X(arguments,1,k)}}},n,n)})();
s.extend({RFC1123:"{Dow}, {dd} {Mon} {yyyy} {HH}:{mm}:{ss} {tz}",RFC1036:"{Weekday}, {dd}-{Mon}-{yy} {HH}:{mm}:{ss} {tz}",ISO8601_DATE:"{yyyy}-{MM}-{dd}",ISO8601_DATETIME:"{yyyy}-{MM}-{dd}T{HH}:{mm}:{ss}.{fff}{isotz}"},n,n);
DateRange=function(a,b){this.start=s.create(a);this.end=s.create(b)};DateRange.prototype.toString=function(){return this.isValid()?this.start.full()+".."+this.end.full():"Invalid DateRange"};
E(DateRange,k,n,{isValid:function(){return this.start<this.end},duration:function(){return this.isValid()?this.end.getTime()-this.start.getTime():NaN},contains:function(a){var b=this;return(a.start&&a.end?[a.start,a.end]:[a]).every(function(c){return c>=b.start&&c<=b.end})},every:function(a,b){var c=this.start.clone(),d=[],e=0,f,h;if(C(a)){c.advance(tb(a,0),k);f=tb(a);h=a.toLowerCase()==="day"}else f={milliseconds:a};for(;c<=this.end;){d.push(c);b&&b(c,e);if(h&&V(c,"Hours")===23){c=c.clone();W(c,
"Hours",48)}else c=c.clone().advance(f,k);e++}return d},union:function(a){return new DateRange(this.start<a.start?this.start:a.start,this.end>a.end?this.end:a.end)},intersect:function(a){return new DateRange(this.start>a.start?this.start:a.start,this.end<a.end?this.end:a.end)},clone:function(){return new DateRange(this.start,this.end)}});H(DateRange,k,n,"Millisecond,Second,Minute,Hour,Day,Week,Month,Year",function(a,b){a["each"+b]=function(c){return this.every(b,c)}});
E(s,n,n,{range:function(a,b){return new DateRange(a,b)}});
function Db(a,b,c,d,e){var f;if(!a.timers)a.timers=[];B(b)||(b=0);a.timers.push(setTimeout(function(){a.timers.splice(f,1);c.apply(d,e||[])},b));f=a.timers.length}
E(Function,k,n,{lazy:function(a,b){function c(){if(!(f&&e.length>b-2)){e.push([this,arguments]);h()}}var d=this,e=[],f=n,h,i,j;a=a||1;b=b||Infinity;i=N(a,void 0,"ceil");j=N(i/a);h=function(){if(!(f||e.length==0)){for(var g=v.max(e.length-j,0);e.length>g;)Function.prototype.apply.apply(d,e.shift());Db(c,i,function(){f=n;h()});f=k}};return c},delay:function(a){var b=F(arguments).slice(1);Db(this,a,this,this,b);return this},throttle:function(a){return this.lazy(a,1)},debounce:function(a){function b(){b.cancel();
Db(b,a,c,this,arguments)}var c=this;return b},cancel:function(){if(da(this.timers))for(;this.timers.length>0;)clearTimeout(this.timers.shift());return this},after:function(a){var b=this,c=0,d=[];if(B(a)){if(a===0){b.call();return b}}else a=1;return function(){var e;d.push(F(arguments));c++;if(c==a){e=b.call(this,d);c=0;d=[];return e}}},once:function(){var a=this;return function(){return L(a,"memo")?a.memo:a.memo=a.apply(this,arguments)}},fill:function(){var a=this,b=F(arguments);return function(){var c=
F(arguments);b.forEach(function(d,e){if(d!=l||e>=c.length)c.splice(e,0,d)});return a.apply(this,c)}}});
function Gb(a,b,c,d,e,f){var h=a.toFixed(20),i=h.search(/\./);h=h.search(/[1-9]/);i=i-h;if(i>0)i-=1;e=v.max(v.min((i/3).floor(),e===n?c.length:e),-d);d=c.charAt(e+d-1);if(i<-9){e=-3;b=i.abs()-9;d=c.slice(0,1)}return(a/(f?(2).pow(10*e):(10).pow(e*3))).round(b||0).format()+d.trim()}
E(u,n,n,{random:function(a,b){var c,d;if(arguments.length==1){b=a;a=0}c=v.min(a||0,K(b)?1:b);d=v.max(a||0,K(b)?1:b)+1;return qa(v.random()*(d-c)+c)}});
E(u,k,n,{log:function(a){return v.log(this)/(a?v.log(a):1)},abbr:function(a){return Gb(this,a,"kmbt",0,4)},metric:function(a,b){return Gb(this,a,"n\u03bcm kMGTPE",4,K(b)?1:b)},bytes:function(a,b){return Gb(this,a,"kMGTPE",0,K(b)?4:b,k)+"B"},isInteger:function(){return this%1==0},isOdd:function(){return!isNaN(this)&&!this.isMultipleOf(2)},isEven:function(){return this.isMultipleOf(2)},isMultipleOf:function(a){return this%a===0},format:function(a,b,c){var d,e,f,h="";if(K(b))b=",";if(K(c))c=".";d=(B(a)?
N(this,a||0).toFixed(v.max(a,0)):this.toString()).replace(/^-/,"").split(".");e=d[0];f=d[1];for(d=e.length;d>0;d-=3){if(d<e.length)h=b+h;h=e.slice(v.max(0,d-3),d)+h}if(f)h+=c+ra((a||0)-f.length,"0")+f;return(this<0?"-":"")+h},hex:function(a){return this.pad(a||1,n,16)},upto:function(a,b,c){return pa(this,a,b,c||1)},downto:function(a,b,c){return pa(this,a,b,-(c||1))},times:function(a){if(a)for(var b=0;b<this;b++)a.call(this,b);return this.toNumber()},chr:function(){return t.fromCharCode(this)},pad:function(a,
b,c){return O(this,a,b,c)},ordinalize:function(){var a=this.abs();a=parseInt(a.toString().slice(-2));return this+sa(a)},toNumber:function(){return parseFloat(this,10)}});H(u,k,n,"round,floor,ceil",function(a,b){a[b]=function(c){return N(this,c,b)}});H(u,k,n,"abs,pow,sin,asin,cos,acos,tan,atan,exp,pow,sqrt",function(a,b){a[b]=function(c,d){return v[b](this,c,d)}});
var Hb="isObject,isNaN".split(","),Ib="keys,values,select,reject,each,merge,clone,equal,watch,tap,has".split(",");
function Jb(a,b,c,d){var e=/^(.+?)(\[.*\])$/,f,h,i;if(d!==n&&(h=b.match(e))){i=h[1];b=h[2].replace(/^\[|\]$/g,"").split("][");b.forEach(function(j){f=!j||j.match(/^\d+$/);if(!i&&da(a))i=a.length;L(a,i)||(a[i]=f?[]:{});a=a[i];i=j});if(!i&&f)i=a.length.toString();Jb(a,i,c)}else a[b]=c.match(/^[+-]?\d+(\.\d+)?$/)?parseFloat(c):c==="true"?k:c==="false"?n:c}function Kb(a,b,c){var d={},e;G(a,function(f,h){e=n;ja(b,function(i){if(D(i)?i.test(f):la(i)?L(i,f):f===t(i))e=k},1);if(e===c)d[f]=h});return d}
E(p,n,k,{watch:function(a,b,c){if(ca){var d=a[b];p.defineProperty(a,b,{enumerable:k,configurable:k,get:function(){return d},set:function(e){d=c.call(a,b,d,e)}})}}});E(p,n,function(a,b){return A(b)},{keys:function(a,b){var c=p.keys(a);c.forEach(function(d){b.call(a,d,a[d])});return c}});
E(p,n,n,{isObject:function(a){return ma(a)},isNaN:function(a){return B(a)&&a.valueOf()!==a.valueOf()},equal:function(a,b){return wa(a)&&wa(b)?va(a)===va(b):a===b},extended:function(a){return new oa(a)},merge:function(a,b,c,d){var e,f;if(a&&typeof b!="string")for(e in b)if(L(b,e)&&a){f=b[e];if(I(a[e])){if(d===n)continue;if(A(d))f=d.call(b,e,a[e],b[e])}if(c===k&&f&&la(f))if(fa(f))f=new s(f.getTime());else if(D(f))f=new r(f.source,ua(f));else{a[e]||(a[e]=q.isArray(f)?[]:{});p.merge(a[e],b[e],c,d);continue}a[e]=
f}return a},values:function(a,b){var c=[];G(a,function(d,e){c.push(e);b&&b.call(a,e)});return c},clone:function(a,b){var c;if(!la(a))return a;c=a instanceof oa?new oa:new a.constructor;return p.merge(c,a,b)},fromQueryString:function(a,b){var c=p.extended();a=a&&a.toString?a.toString():"";a.replace(/^.*?\?/,"").split("&").forEach(function(d){d=d.split("=");d.length===2&&Jb(c,d[0],decodeURIComponent(d[1]),b)});return c},tap:function(a,b){var c=b;A(b)||(c=function(){b&&a[b]()});c.call(a,a);return a},
has:function(a,b){return L(a,b)},select:function(a){return Kb(a,arguments,k)},reject:function(a){return Kb(a,arguments,n)}});H(p,n,n,x,function(a,b){var c="is"+b;Hb.push(c);a[c]=function(d){return p.prototype.toString.call(d)==="[object "+b+"]"}});(function(){E(p,n,function(){return arguments.length===0},{extend:function(){var a=Hb.concat(Ib);if(typeof Za!=="undefined")a=a.concat(Za);ya(a,p)}})})();ya(Ib,oa);
E(r,n,n,{escape:function(a){return P(a)}});
E(r,k,n,{getFlags:function(){return ua(this)},setFlags:function(a){return r(this.source,a)},addFlag:function(a){return this.setFlags(ua(this,a))},removeFlag:function(a){return this.setFlags(ua(this).replace(a,""))}});
var Lb,Mb;
E(t,k,function(a){return D(a)||arguments.length>2},{startsWith:function(a,b,c){var d=this;if(b)d=d.slice(b);if(K(c))c=k;a=D(a)?a.source.replace("^",""):P(a);return r("^"+a,c?"":"i").test(d)},endsWith:function(a,b,c){var d=this;if(I(b))d=d.slice(0,b);if(K(c))c=k;a=D(a)?a.source.replace("$",""):P(a);return r(a+"$",c?"":"i").test(d)}});
E(t,k,n,{escapeRegExp:function(){return P(this)},escapeURL:function(a){return a?encodeURIComponent(this):encodeURI(this)},unescapeURL:function(a){return a?decodeURI(this):decodeURIComponent(this)},escapeHTML:function(){return this.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&apos;").replace(/\//g,"&#x2f;")},unescapeHTML:function(){return this.replace(/&amp;/g,"&").replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/&quot;/g,'"').replace(/&apos;/g,
"'").replace(/&#x2f;/g,"/")},encodeBase64:function(){return Lb(this)},decodeBase64:function(){return Mb(this)},each:function(a,b){var c,d;if(A(a)){b=a;a=/[\s\S]/g}else if(a)if(C(a))a=r(P(a),"gi");else{if(D(a))a=r(a.source,ua(a,"g"))}else a=/[\s\S]/g;c=this.match(a)||[];if(b)for(d=0;d<c.length;d++)c[d]=b.call(this,c[d],d,c)||c[d];return c},shift:function(a){var b="";a=a||0;this.codes(function(c){b+=t.fromCharCode(c+a)});return b},codes:function(a){for(var b=[],c=0;c<this.length;c++){var d=this.charCodeAt(c);
b.push(d);a&&a.call(this,d,c)}return b},chars:function(a){return this.each(a)},words:function(a){return this.trim().each(/\S+/g,a)},lines:function(a){return this.trim().each(/^.*$/gm,a)},paragraphs:function(a){var b=this.trim().split(/[\r\n]{2,}/);return b=b.map(function(c){if(a)var d=a.call(c);return d?d:c})},isBlank:function(){return this.trim().length===0},has:function(a){return this.search(D(a)?a:P(a))!==-1},add:function(a,b){b=K(b)?this.length:b;return this.slice(0,b)+a+this.slice(b)},remove:function(a){return this.replace(a,
"")},reverse:function(){return this.split("").reverse().join("")},compact:function(){return this.trim().replace(/([\r\n\s\u3000])+/g,function(a,b){return b==="\u3000"?b:" "})},at:function(){return xa(this,arguments,k)},from:function(a){return this.slice(a)},to:function(a){if(K(a))a=this.length;return this.slice(0,a)},dasherize:function(){return this.underscore().replace(/_/g,"-")},underscore:function(){return this.replace(/[-\s]+/g,"_").replace(t.Inflector&&t.Inflector.acronymRegExp,function(a,b){return(b>
0?"_":"")+a.toLowerCase()}).replace(/([A-Z\d]+)([A-Z][a-z])/g,"$1_$2").replace(/([a-z\d])([A-Z])/g,"$1_$2").toLowerCase()},camelize:function(a){return this.underscore().replace(/(^|_)([^_]+)/g,function(b,c,d,e){b=d;b=(c=t.Inflector)&&c.acronyms[b];b=C(b)?b:void 0;e=a!==n||e>0;if(b)return e?b:b.toLowerCase();return e?d.capitalize():d})},spacify:function(){return this.underscore().replace(/_/g," ")},stripTags:function(){var a=this;ja(arguments.length>0?arguments:[""],function(b){a=a.replace(r("</?"+
P(b)+"[^<>]*>","gi"),"")});return a},removeTags:function(){var a=this;ja(arguments.length>0?arguments:["\\S+"],function(b){b=r("<("+b+")[^<>]*(?:\\/>|>.*?<\\/\\1>)","gi");a=a.replace(b,"")});return a},truncate:function(a,b,c,d){var e="",f="",h=this.toString(),i="["+ta()+"]+",j="[^"+ta()+"]*",g=r(i+j+"$");d=K(d)?"...":t(d);if(h.length<=a)return h;switch(c){case "left":a=h.length-a;e=d;h=h.slice(a);g=r("^"+j+i);break;case "middle":a=qa(a/2);f=d+h.slice(h.length-a).trimLeft();h=h.slice(0,a);break;default:a=
a;f=d;h=h.slice(0,a)}if(b===n&&this.slice(a,a+1).match(/\S/))h=h.remove(g);return e+h+f},pad:function(a,b){return ra(b,a)+this+ra(b,a)},padLeft:function(a,b){return ra(b,a)+this},padRight:function(a,b){return this+ra(b,a)},first:function(a){if(K(a))a=1;return this.substr(0,a)},last:function(a){if(K(a))a=1;return this.substr(this.length-a<0?0:this.length-a)},repeat:function(a){var b="",c=this;if(!B(a)||a<1)return"";for(;a;){if(a&1)b+=c;if(a>>=1)c+=c}return b},toNumber:function(a){var b=this.replace(/,/g,
"");return b.match(/\./)?parseFloat(b):parseInt(b,a||10)},capitalize:function(a){var b;return this.toLowerCase().replace(a?/[\s\S]/g:/^\S/,function(c){var d=c.toUpperCase(),e;e=b?c:d;b=d!==c;return e})},assign:function(){var a={};F(arguments,function(b,c){if(ma(b))na(a,b);else a[c+1]=b});return this.replace(/\{([^{]+?)\}/g,function(b,c){return L(a,c)?a[c]:b})},namespace:function(a){a=a||ba;G(this.split("."),function(b,c){return!!(a=a[c])});return a}});E(t,k,n,{insert:t.prototype.add});
(function(a){if(this.btoa){Lb=this.btoa;Mb=this.atob}else{var b=/[^A-Za-z0-9\+\/\=]/g;Lb=function(c){var d="",e,f,h,i,j,g,m=0;do{e=c.charCodeAt(m++);f=c.charCodeAt(m++);h=c.charCodeAt(m++);i=e>>2;e=(e&3)<<4|f>>4;j=(f&15)<<2|h>>6;g=h&63;if(isNaN(f))j=g=64;else if(isNaN(h))g=64;d=d+a.charAt(i)+a.charAt(e)+a.charAt(j)+a.charAt(g)}while(m<c.length);return d};Mb=function(c){var d="",e,f,h,i,j,g=0;if(c.match(b))throw Error("String contains invalid base64 characters");c=c.replace(/[^A-Za-z0-9\+\/\=]/g,"");
do{e=a.indexOf(c.charAt(g++));f=a.indexOf(c.charAt(g++));i=a.indexOf(c.charAt(g++));j=a.indexOf(c.charAt(g++));e=e<<2|f>>4;f=(f&15)<<4|i>>2;h=(i&3)<<6|j;d+=t.fromCharCode(e);if(i!=64)d+=t.fromCharCode(f);if(j!=64)d+=t.fromCharCode(h)}while(g<c.length);return d}}})("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=");})();
/*!
xCharts v0.1.2 Copyright (c) 2012, tenXer, Inc. All Rights Reserved.
@license MIT license. http://github.com/tenXer/xcharts for details
*/
(function(){var xChart,_vis={},_scales={},_visutils={};(function(){var n=this,t=n._,r={},e=Array.prototype,u=Object.prototype,i=Function.prototype,a=e.push,o=e.slice,c=e.concat,l=u.toString,f=u.hasOwnProperty,s=e.forEach,p=e.map,v=e.reduce,h=e.reduceRight,g=e.filter,d=e.every,m=e.some,y=e.indexOf,b=e.lastIndexOf,x=Array.isArray,_=Object.keys,j=i.bind,w=function(n){return n instanceof w?n:this instanceof w?(this._wrapped=n,void 0):new w(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=w),exports._=w):n._=w,w.VERSION="1.4.3";var A=w.each=w.forEach=function(n,t,e){if(null!=n)if(s&&n.forEach===s)n.forEach(t,e);else if(n.length===+n.length){for(var u=0,i=n.length;i>u;u++)if(t.call(e,n[u],u,n)===r)return}else for(var a in n)if(w.has(n,a)&&t.call(e,n[a],a,n)===r)return};w.map=w.collect=function(n,t,r){var e=[];return null==n?e:p&&n.map===p?n.map(t,r):(A(n,function(n,u,i){e[e.length]=t.call(r,n,u,i)}),e)};var O="Reduce of empty array with no initial value";w.reduce=w.foldl=w.inject=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),v&&n.reduce===v)return e&&(t=w.bind(t,e)),u?n.reduce(t,r):n.reduce(t);if(A(n,function(n,i,a){u?r=t.call(e,r,n,i,a):(r=n,u=!0)}),!u)throw new TypeError(O);return r},w.reduceRight=w.foldr=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),h&&n.reduceRight===h)return e&&(t=w.bind(t,e)),u?n.reduceRight(t,r):n.reduceRight(t);var i=n.length;if(i!==+i){var a=w.keys(n);i=a.length}if(A(n,function(o,c,l){c=a?a[--i]:--i,u?r=t.call(e,r,n[c],c,l):(r=n[c],u=!0)}),!u)throw new TypeError(O);return r},w.find=w.detect=function(n,t,r){var e;return E(n,function(n,u,i){return t.call(r,n,u,i)?(e=n,!0):void 0}),e},w.filter=w.select=function(n,t,r){var e=[];return null==n?e:g&&n.filter===g?n.filter(t,r):(A(n,function(n,u,i){t.call(r,n,u,i)&&(e[e.length]=n)}),e)},w.reject=function(n,t,r){return w.filter(n,function(n,e,u){return!t.call(r,n,e,u)},r)},w.every=w.all=function(n,t,e){t||(t=w.identity);var u=!0;return null==n?u:d&&n.every===d?n.every(t,e):(A(n,function(n,i,a){return(u=u&&t.call(e,n,i,a))?void 0:r}),!!u)};var E=w.some=w.any=function(n,t,e){t||(t=w.identity);var u=!1;return null==n?u:m&&n.some===m?n.some(t,e):(A(n,function(n,i,a){return u||(u=t.call(e,n,i,a))?r:void 0}),!!u)};w.contains=w.include=function(n,t){return null==n?!1:y&&n.indexOf===y?-1!=n.indexOf(t):E(n,function(n){return n===t})},w.invoke=function(n,t){var r=o.call(arguments,2);return w.map(n,function(n){return(w.isFunction(t)?t:n[t]).apply(n,r)})},w.pluck=function(n,t){return w.map(n,function(n){return n[t]})},w.where=function(n,t){return w.isEmpty(t)?[]:w.filter(n,function(n){for(var r in t)if(t[r]!==n[r])return!1;return!0})},w.max=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.max.apply(Math,n);if(!t&&w.isEmpty(n))return-1/0;var e={computed:-1/0,value:-1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;a>=e.computed&&(e={value:n,computed:a})}),e.value},w.min=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.min.apply(Math,n);if(!t&&w.isEmpty(n))return 1/0;var e={computed:1/0,value:1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;e.computed>a&&(e={value:n,computed:a})}),e.value},w.shuffle=function(n){var t,r=0,e=[];return A(n,function(n){t=w.random(r++),e[r-1]=e[t],e[t]=n}),e};var F=function(n){return w.isFunction(n)?n:function(t){return t[n]}};w.sortBy=function(n,t,r){var e=F(t);return w.pluck(w.map(n,function(n,t,u){return{value:n,index:t,criteria:e.call(r,n,t,u)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||void 0===r)return 1;if(e>r||void 0===e)return-1}return n.index<t.index?-1:1}),"value")};var k=function(n,t,r,e){var u={},i=F(t||w.identity);return A(n,function(t,a){var o=i.call(r,t,a,n);e(u,o,t)}),u};w.groupBy=function(n,t,r){return k(n,t,r,function(n,t,r){(w.has(n,t)?n[t]:n[t]=[]).push(r)})},w.countBy=function(n,t,r){return k(n,t,r,function(n,t){w.has(n,t)||(n[t]=0),n[t]++})},w.sortedIndex=function(n,t,r,e){r=null==r?w.identity:F(r);for(var u=r.call(e,t),i=0,a=n.length;a>i;){var o=i+a>>>1;u>r.call(e,n[o])?i=o+1:a=o}return i},w.toArray=function(n){return n?w.isArray(n)?o.call(n):n.length===+n.length?w.map(n,w.identity):w.values(n):[]},w.size=function(n){return null==n?0:n.length===+n.length?n.length:w.keys(n).length},w.first=w.head=w.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:o.call(n,0,t)},w.initial=function(n,t,r){return o.call(n,0,n.length-(null==t||r?1:t))},w.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:o.call(n,Math.max(n.length-t,0))},w.rest=w.tail=w.drop=function(n,t,r){return o.call(n,null==t||r?1:t)},w.compact=function(n){return w.filter(n,w.identity)};var R=function(n,t,r){return A(n,function(n){w.isArray(n)?t?a.apply(r,n):R(n,t,r):r.push(n)}),r};w.flatten=function(n,t){return R(n,t,[])},w.without=function(n){return w.difference(n,o.call(arguments,1))},w.uniq=w.unique=function(n,t,r,e){w.isFunction(t)&&(e=r,r=t,t=!1);var u=r?w.map(n,r,e):n,i=[],a=[];return A(u,function(r,e){(t?e&&a[a.length-1]===r:w.contains(a,r))||(a.push(r),i.push(n[e]))}),i},w.union=function(){return w.uniq(c.apply(e,arguments))},w.intersection=function(n){var t=o.call(arguments,1);return w.filter(w.uniq(n),function(n){return w.every(t,function(t){return w.indexOf(t,n)>=0})})},w.difference=function(n){var t=c.apply(e,o.call(arguments,1));return w.filter(n,function(n){return!w.contains(t,n)})},w.zip=function(){for(var n=o.call(arguments),t=w.max(w.pluck(n,"length")),r=Array(t),e=0;t>e;e++)r[e]=w.pluck(n,""+e);return r},w.object=function(n,t){if(null==n)return{};for(var r={},e=0,u=n.length;u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},w.indexOf=function(n,t,r){if(null==n)return-1;var e=0,u=n.length;if(r){if("number"!=typeof r)return e=w.sortedIndex(n,t),n[e]===t?e:-1;e=0>r?Math.max(0,u+r):r}if(y&&n.indexOf===y)return n.indexOf(t,r);for(;u>e;e++)if(n[e]===t)return e;return-1},w.lastIndexOf=function(n,t,r){if(null==n)return-1;var e=null!=r;if(b&&n.lastIndexOf===b)return e?n.lastIndexOf(t,r):n.lastIndexOf(t);for(var u=e?r:n.length;u--;)if(n[u]===t)return u;return-1},w.range=function(n,t,r){1>=arguments.length&&(t=n||0,n=0),r=arguments[2]||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=0,i=Array(e);e>u;)i[u++]=n,n+=r;return i};var I=function(){};w.bind=function(n,t){var r,e;if(n.bind===j&&j)return j.apply(n,o.call(arguments,1));if(!w.isFunction(n))throw new TypeError;return r=o.call(arguments,2),e=function(){if(!(this instanceof e))return n.apply(t,r.concat(o.call(arguments)));I.prototype=n.prototype;var u=new I;I.prototype=null;var i=n.apply(u,r.concat(o.call(arguments)));return Object(i)===i?i:u}},w.bindAll=function(n){var t=o.call(arguments,1);return 0==t.length&&(t=w.functions(n)),A(t,function(t){n[t]=w.bind(n[t],n)}),n},w.memoize=function(n,t){var r={};return t||(t=w.identity),function(){var e=t.apply(this,arguments);return w.has(r,e)?r[e]:r[e]=n.apply(this,arguments)}},w.delay=function(n,t){var r=o.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},w.defer=function(n){return w.delay.apply(w,[n,1].concat(o.call(arguments,1)))},w.throttle=function(n,t){var r,e,u,i,a=0,o=function(){a=new Date,u=null,i=n.apply(r,e)};return function(){var c=new Date,l=t-(c-a);return r=this,e=arguments,0>=l?(clearTimeout(u),u=null,a=c,i=n.apply(r,e)):u||(u=setTimeout(o,l)),i}},w.debounce=function(n,t,r){var e,u;return function(){var i=this,a=arguments,o=function(){e=null,r||(u=n.apply(i,a))},c=r&&!e;return clearTimeout(e),e=setTimeout(o,t),c&&(u=n.apply(i,a)),u}},w.once=function(n){var t,r=!1;return function(){return r?t:(r=!0,t=n.apply(this,arguments),n=null,t)}},w.wrap=function(n,t){return function(){var r=[n];return a.apply(r,arguments),t.apply(this,r)}},w.compose=function(){var n=arguments;return function(){for(var t=arguments,r=n.length-1;r>=0;r--)t=[n[r].apply(this,t)];return t[0]}},w.after=function(n,t){return 0>=n?t():function(){return 1>--n?t.apply(this,arguments):void 0}},w.keys=_||function(n){if(n!==Object(n))throw new TypeError("Invalid object");var t=[];for(var r in n)w.has(n,r)&&(t[t.length]=r);return t},w.values=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push(n[r]);return t},w.pairs=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push([r,n[r]]);return t},w.invert=function(n){var t={};for(var r in n)w.has(n,r)&&(t[n[r]]=r);return t},w.functions=w.methods=function(n){var t=[];for(var r in n)w.isFunction(n[r])&&t.push(r);return t.sort()},w.extend=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)n[r]=t[r]}),n},w.pick=function(n){var t={},r=c.apply(e,o.call(arguments,1));return A(r,function(r){r in n&&(t[r]=n[r])}),t},w.omit=function(n){var t={},r=c.apply(e,o.call(arguments,1));for(var u in n)w.contains(r,u)||(t[u]=n[u]);return t},w.defaults=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)null==n[r]&&(n[r]=t[r])}),n},w.clone=function(n){return w.isObject(n)?w.isArray(n)?n.slice():w.extend({},n):n},w.tap=function(n,t){return t(n),n};var S=function(n,t,r,e){if(n===t)return 0!==n||1/n==1/t;if(null==n||null==t)return n===t;n instanceof w&&(n=n._wrapped),t instanceof w&&(t=t._wrapped);var u=l.call(n);if(u!=l.call(t))return!1;switch(u){case"[object String]":return n==t+"";case"[object Number]":return n!=+n?t!=+t:0==n?1/n==1/t:n==+t;case"[object Date]":case"[object Boolean]":return+n==+t;case"[object RegExp]":return n.source==t.source&&n.global==t.global&&n.multiline==t.multiline&&n.ignoreCase==t.ignoreCase}if("object"!=typeof n||"object"!=typeof t)return!1;for(var i=r.length;i--;)if(r[i]==n)return e[i]==t;r.push(n),e.push(t);var a=0,o=!0;if("[object Array]"==u){if(a=n.length,o=a==t.length)for(;a--&&(o=S(n[a],t[a],r,e)););}else{var c=n.constructor,f=t.constructor;if(c!==f&&!(w.isFunction(c)&&c instanceof c&&w.isFunction(f)&&f instanceof f))return!1;for(var s in n)if(w.has(n,s)&&(a++,!(o=w.has(t,s)&&S(n[s],t[s],r,e))))break;if(o){for(s in t)if(w.has(t,s)&&!a--)break;o=!a}}return r.pop(),e.pop(),o};w.isEqual=function(n,t){return S(n,t,[],[])},w.isEmpty=function(n){if(null==n)return!0;if(w.isArray(n)||w.isString(n))return 0===n.length;for(var t in n)if(w.has(n,t))return!1;return!0},w.isElement=function(n){return!(!n||1!==n.nodeType)},w.isArray=x||function(n){return"[object Array]"==l.call(n)},w.isObject=function(n){return n===Object(n)},A(["Arguments","Function","String","Number","Date","RegExp"],function(n){w["is"+n]=function(t){return l.call(t)=="[object "+n+"]"}}),w.isArguments(arguments)||(w.isArguments=function(n){return!(!n||!w.has(n,"callee"))}),w.isFunction=function(n){return"function"==typeof n},w.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},w.isNaN=function(n){return w.isNumber(n)&&n!=+n},w.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"==l.call(n)},w.isNull=function(n){return null===n},w.isUndefined=function(n){return void 0===n},w.has=function(n,t){return f.call(n,t)},w.noConflict=function(){return n._=t,this},w.identity=function(n){return n},w.times=function(n,t,r){for(var e=Array(n),u=0;n>u;u++)e[u]=t.call(r,u);return e},w.random=function(n,t){return null==t&&(t=n,n=0),n+(0|Math.random()*(t-n+1))};var T={escape:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","/":"&#x2F;"}};T.unescape=w.invert(T.escape);var M={escape:RegExp("["+w.keys(T.escape).join("")+"]","g"),unescape:RegExp("("+w.keys(T.unescape).join("|")+")","g")};w.each(["escape","unescape"],function(n){w[n]=function(t){return null==t?"":(""+t).replace(M[n],function(t){return T[n][t]})}}),w.result=function(n,t){if(null==n)return null;var r=n[t];return w.isFunction(r)?r.call(n):r},w.mixin=function(n){A(w.functions(n),function(t){var r=w[t]=n[t];w.prototype[t]=function(){var n=[this._wrapped];return a.apply(n,arguments),z.call(this,r.apply(w,n))}})};var N=0;w.uniqueId=function(n){var t=""+ ++N;return n?n+t:t},w.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var q=/(.)^/,B={"'":"'","\\":"\\","\r":"r","\n":"n","	":"t","\u2028":"u2028","\u2029":"u2029"},D=/\\|'|\r|\n|\t|\u2028|\u2029/g;w.template=function(n,t,r){r=w.defaults({},r,w.templateSettings);var e=RegExp([(r.escape||q).source,(r.interpolate||q).source,(r.evaluate||q).source].join("|")+"|$","g"),u=0,i="__p+='";n.replace(e,function(t,r,e,a,o){return i+=n.slice(u,o).replace(D,function(n){return"\\"+B[n]}),r&&(i+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'"),e&&(i+="'+\n((__t=("+e+"))==null?'':__t)+\n'"),a&&(i+="';\n"+a+"\n__p+='"),u=o+t.length,t}),i+="';\n",r.variable||(i="with(obj||{}){\n"+i+"}\n"),i="var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n"+i+"return __p;\n";try{var a=Function(r.variable||"obj","_",i)}catch(o){throw o.source=i,o}if(t)return a(t,w);var c=function(n){return a.call(this,n,w)};return c.source="function("+(r.variable||"obj")+"){\n"+i+"}",c},w.chain=function(n){return w(n).chain()};var z=function(n){return this._chain?w(n).chain():n};w.mixin(w),A(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=e[n];w.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!=n&&"splice"!=n||0!==r.length||delete r[0],z.call(this,r)}}),A(["concat","join","slice"],function(n){var t=e[n];w.prototype[n]=function(){return z.call(this,t.apply(this._wrapped,arguments))}}),w.extend(w.prototype,{chain:function(){return this._chain=!0,this},value:function(){return this._wrapped}})}).call(this);function getInsertionPoint(zIndex){return _.chain(_.range(zIndex,10)).reverse().map(function(z){return'g[data-index="'+z+'"]'}).value().join(", ")}function colorClass(el,i){var c=el.getAttribute("class");return(c!==null?c.replace(/color\d+/g,""):"")+" color"+i}_visutils={getInsertionPoint:getInsertionPoint,colorClass:colorClass};var local=this,defaultSpacing=.25;function _getDomain(data,axis){return _.chain(data).pluck("data").flatten().pluck(axis).uniq().filter(function(d){return d!==undefined&&d!==null}).value().sort(d3.ascending)}function _extendDomain(domain,axis){var min=domain[0],max=domain[1],diff,e;if(min===max){e=Math.max(Math.round(min/10),4);min-=e;max+=e}diff=max-min;min=min?min-diff/10:min;min=domain[0]>0?Math.max(min,0):min;max=max?max+diff/10:max;max=domain[1]<0?Math.min(max,0):max;return[min,max]}function ordinal(data,axis,bounds,spacing){spacing=spacing||defaultSpacing;var domain=_getDomain(data,axis);return d3.scale.ordinal().domain(domain).rangeRoundBands(bounds,spacing)}function linear(extents,bounds,axis){if(axis==="y"){extents=_extendDomain(extents,axis)}return d3.scale.linear().domain(extents).nice().rangeRound(bounds)}function exponential(extents,bounds,axis){if(axis==="y"){extents=_extendDomain(extents,axis)}return d3.scale.pow().exponent(.65).domain(extents).nice().rangeRound(bounds)}function time(extents,bounds){return d3.time.scale().domain(_.map(extents,function(d){return new Date(d)})).range(bounds)}function _getExtents(data,key){var nData=_.chain(data).pluck("data").flatten().value();return{x:d3.extent(nData,function(d){return d.x}),y:d3.extent(nData,function(d){return d.y})}}function xy(self,data,xType,yType){var extents=_getExtents(data),scales={},o=self._options,horiz=[o.axisPaddingLeft,self._width],vert=[self._height,o.axisPaddingTop],xScale,yScale;_.each([xType,yType],function(type,i){var axis=i===0?"x":"y",bounds=i===0?horiz:vert;switch(type){case"ordinal":scales[axis]=ordinal(data,axis,bounds);break;case"linear":scales[axis]=linear(extents[axis],bounds,axis);break;case"exponential":scales[axis]=exponential(extents[axis],bounds,axis);break;case"time":scales[axis]=time(extents[axis],bounds);break}});return scales}var _scales={ordinal:ordinal,linear:linear,exponential:exponential,time:time,xy:xy};(function(){var zIndex=2,selector="g.bar",insertBefore=_visutils.getInsertionPoint(zIndex);function postUpdateScale(self,scaleData,mainData,compData){self.xScale2=d3.scale.ordinal().domain(d3.range(0,mainData.length)).rangeRoundBands([0,self.xScale.rangeBand()],.08)}function enter(self,storage,className,data,callbacks){var barGroups,bars,yZero=self.yZero;barGroups=self._g.selectAll(selector+className).data(data,function(d){return d.className});barGroups.enter().insert("g",insertBefore).attr("data-index",zIndex).style("opacity",0).attr("class",function(d,i){var cl=_.uniq((className+d.className).split(".")).join(" ");return cl+" bar "+_visutils.colorClass(this,i)}).attr("transform",function(d,i){return"translate("+self.xScale2(i)+",0)"});bars=barGroups.selectAll("rect").data(function(d){return d.data},function(d){return d.x});bars.enter().append("rect").attr("width",0).attr("rx",3).attr("ry",3).attr("x",function(d){return self.xScale(d.x)+self.xScale2.rangeBand()/2}).attr("height",function(d){return Math.abs(yZero-self.yScale(d.y))}).attr("y",function(d){return d.y<0?yZero:self.yScale(d.y)}).on("mouseover",callbacks.mouseover).on("mouseout",callbacks.mouseout).on("click",callbacks.click);storage.barGroups=barGroups;storage.bars=bars}function update(self,storage,timing){var yZero=self.yZero;storage.barGroups.attr("class",function(d,i){return _visutils.colorClass(this,i)}).transition().duration(timing).style("opacity",1).attr("transform",function(d,i){return"translate("+self.xScale2(i)+",0)"});storage.bars.transition().duration(timing).attr("width",self.xScale2.rangeBand()).attr("x",function(d){return self.xScale(d.x)}).attr("height",function(d){return Math.abs(yZero-self.yScale(d.y))}).attr("y",function(d){return d.y<0?yZero:self.yScale(d.y)})}function exit(self,storage,timing){storage.bars.exit().transition().duration(timing).attr("width",0).remove();storage.barGroups.exit().transition().duration(timing).style("opacity",0).remove()}function destroy(self,storage,timing){var band=self.xScale2?self.xScale2.rangeBand()/2:0;delete self.xScale2;storage.bars.transition().duration(timing).attr("width",0).attr("x",function(d){return self.xScale(d.x)+band})}_vis.bar={postUpdateScale:postUpdateScale,enter:enter,update:update,exit:exit,destroy:destroy}})();(function(){var zIndex=3,selector="g.line",insertBefore=_visutils.getInsertionPoint(zIndex);function enter(self,storage,className,data,callbacks){var inter=self._options.interpolation,x=function(d,i){if(!self.xScale2&&!self.xScale.rangeBand){return self.xScale(d.x)}return self.xScale(d.x)+self.xScale.rangeBand()/2},y=function(d){return self.yScale(d.y)},line=d3.svg.line().x(x).interpolate(inter),area=d3.svg.area().x(x).y1(self.yZero).interpolate(inter),container,fills,paths;function datum(d){return[d.data]}container=self._g.selectAll(selector+className).data(data,function(d){return d.className});container.enter().insert("g",insertBefore).attr("data-index",zIndex).attr("class",function(d,i){var cl=_.uniq((className+d.className).split(".")).join(" ");return cl+" line "+_visutils.colorClass(this,i)});fills=container.selectAll("path.fill").data(datum);fills.enter().append("path").attr("class","fill").style("opacity",0).attr("d",area.y0(y));paths=container.selectAll("path.line").data(datum);paths.enter().append("path").attr("class","line").style("opacity",0).attr("d",line.y(y));storage.lineContainers=container;storage.lineFills=fills;storage.linePaths=paths;storage.lineX=x;storage.lineY=y;storage.lineA=area;storage.line=line}function update(self,storage,timing){storage.lineContainers.attr("class",function(d,i){return _visutils.colorClass(this,i)});storage.lineFills.transition().duration(timing).style("opacity",1).attr("d",storage.lineA.y0(storage.lineY));storage.linePaths.transition().duration(timing).style("opacity",1).attr("d",storage.line.y(storage.lineY))}function exit(self,storage){storage.linePaths.exit().style("opacity",0).remove();storage.lineFills.exit().style("opacity",0).remove();storage.lineContainers.exit().remove()}function destroy(self,storage,timing){storage.linePaths.transition().duration(timing).style("opacity",0);storage.lineFills.transition().duration(timing).style("opacity",0)}_vis.line={enter:enter,update:update,exit:exit,destroy:destroy}})();(function(){var line=_vis.line;function enter(self,storage,className,data,callbacks){var circles;line.enter(self,storage,className,data,callbacks);circles=storage.lineContainers.selectAll("circle").data(function(d){return d.data},function(d){return d.x});circles.enter().append("circle").style("opacity",0).attr("cx",storage.lineX).attr("cy",storage.lineY).attr("r",5).on("mouseover",callbacks.mouseover).on("mouseout",callbacks.mouseout).on("click",callbacks.click);storage.lineCircles=circles}function update(self,storage,timing){line.update.apply(null,_.toArray(arguments));storage.lineCircles.transition().duration(timing).style("opacity",1).attr("cx",storage.lineX).attr("cy",storage.lineY)}function exit(self,storage){storage.lineCircles.exit().remove();line.exit.apply(null,_.toArray(arguments))}function destroy(self,storage,timing){line.destroy.apply(null,_.toArray(arguments));if(!storage.lineCircles){return}storage.lineCircles.transition().duration(timing).style("opacity",0)}_vis["line-dotted"]={enter:enter,update:update,exit:exit,destroy:destroy}})();(function(){var line=_vis["line-dotted"];function enter(self,storage,className,data,callbacks){line.enter(self,storage,className,data,callbacks)}function _accumulate_data(data){function reduce(memo,num){return memo+num.y}var nData=_.map(data,function(set){var i=set.data.length,d=_.clone(set.data);set=_.clone(set);while(i){i-=1;d[i]=_.clone(set.data[i]);d[i].y0=set.data[i].y;d[i].y=_.reduce(_.first(set.data,i),reduce,set.data[i].y)}return _.extend(set,{data:d})});return nData}function _resetData(self){if(!self.hasOwnProperty("cumulativeOMainData")){return}self._mainData=self.cumulativeOMainData;delete self.cumulativeOMainData;self._compData=self.cumulativeOCompData;delete self.cumulativeOCompData}function preUpdateScale(self,data){_resetData(self);self.cumulativeOMainData=self._mainData;self._mainData=_accumulate_data(self._mainData);self.cumulativeOCompData=self._compData;self._compData=_accumulate_data(self._compData)}function destroy(self,storage,timing){_resetData(self);line.destroy.apply(null,_.toArray(arguments))}_vis.cumulative={preUpdateScale:preUpdateScale,enter:enter,update:line.update,exit:line.exit,destroy:destroy}})();var emptyData=[[]],defaults={mouseover:function(data,i){},mouseout:function(data,i){},click:function(data,i){},axisPaddingTop:0,axisPaddingRight:0,axisPaddingBottom:5,axisPaddingLeft:20,paddingTop:0,paddingRight:0,paddingBottom:20,paddingLeft:60,tickHintX:10,tickFormatX:function(x){return x},tickHintY:10,tickFormatY:function(y){return y},dataFormatX:function(x){return x},dataFormatY:function(y){return y},unsupported:function(selector){d3.select(selector).text("SVG is not supported on your browser")},empty:function(self,selector,d){},notempty:function(self,selector){},timing:750,interpolation:"monotone"};function svgEnabled(){var d=document;return!!d.createElementNS&&!!d.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect}function xChart(type,data,selector,options){var self=this,resizeLock;self._options=options=_.defaults(options||{},defaults);if(svgEnabled()===false){return options.unsupported(selector)}self._selector=selector;self._container=d3.select(selector);self._drawSvg();data=_.clone(data);if(type&&!data.type){data.type=type}self.setData(data);d3.select(window).on("resize.for."+selector,function(){if(resizeLock){clearTimeout(resizeLock)}resizeLock=setTimeout(function(){resizeLock=null;self._resize()},500)})}xChart.setVis=function(type,vis){if(_vis.hasOwnProperty(type)){throw'Cannot override vis type "'+type+'".'}_vis[type]=vis};xChart.getVis=function(type){if(!_vis.hasOwnProperty(type)){throw'Vis type "'+type+'" does not exist.'}return _.clone(_vis[type])};xChart.visutils=_visutils;xChart.scales=_scales;_.defaults(xChart.prototype,{setType:function(type,skipDraw){var self=this;if(self._type&&type===self._type){return}if(!_vis.hasOwnProperty(type)){throw'Vis type "'+type+'" is not defined.'}if(self._type){self._destroy(self._vis,self._mainStorage)}self._type=type;self._vis=_vis[type];if(!skipDraw){self._draw()}},setData:function(data){var self=this,o=self._options,nData=_.clone(data);if(!data.hasOwnProperty("main")){throw'No "main" key found in given chart data.'}switch(data.type){case"bar":data.xScale="ordinal";break;case undefined:data.type=self._type;break}if(self._vis){self._destroy(self._vis,self._mainStorage)}self.setType(data.type,true);function _mapData(set){var d=_.map(_.clone(set.data),function(p){var np=_.clone(p);if(p.hasOwnProperty("x")){np.x=o.dataFormatX(p.x)}if(p.hasOwnProperty("y")){np.y=o.dataFormatY(p.y)}return np}).sort(function(a,b){if(!a.x&&!b.x){return 0}return a.x<b.x?-1:1});return _.extend(_.clone(set),{data:d})}nData.main=_.map(nData.main,_mapData);self._mainData=nData.main;self._xScaleType=nData.xScale;self._yScaleType=nData.yScale;if(nData.hasOwnProperty("comp")){nData.comp=_.map(nData.comp,_mapData);self._compData=nData.comp}else{self._compData=[]}self._draw()},setScale:function(axis,type){var self=this;switch(axis){case"x":self._xScaleType=type;break;case"y":self._yScaleType=type;break;default:throw'Cannot change scale of unknown axis "'+axis+'".'}self._draw()},_drawSvg:function(){var self=this,c=self._container,options=self._options,width=parseInt(c.style("width").replace("px",""),10),height=parseInt(c.style("height").replace("px",""),10),svg,g,gScale;svg=c.selectAll("svg").data(emptyData);svg.enter().append("svg").attr("height",height).attr("width",width).attr("class","xchart");svg.transition().attr("width",width).attr("height",height);g=svg.selectAll("g").data(emptyData);g.enter().append("g").attr("transform","translate("+options.paddingLeft+","+options.paddingTop+")");gScale=g.selectAll("g.scale").data(emptyData);gScale.enter().append("g").attr("class","scale");self._svg=svg;self._g=g;self._gScale=gScale;self._height=height-options.paddingTop-options.paddingBottom-options.axisPaddingTop-options.axisPaddingBottom;self._width=width-options.paddingLeft-options.paddingRight-options.axisPaddingLeft-options.axisPaddingRight},_resize:function(event){var self=this;self._drawSvg();self._draw()},_drawAxes:function(){if(this._noData){return}var self=this,o=self._options,t=self._gScale.transition().duration(o.timing),xTicks=o.tickHintX,yTicks=o.tickHintY,bottom=self._height+o.axisPaddingTop+o.axisPaddingBottom,zeroLine=d3.svg.line().x(function(d){return d}),zLine,zLinePath,xAxis,xRules,yAxis,yRules,labels;xRules=d3.svg.axis().scale(self.xScale).ticks(xTicks).tickSize(-self._height).tickFormat(o.tickFormatX).orient("bottom");xAxis=self._gScale.selectAll("g.axisX").data(emptyData);xAxis.enter().append("g").attr("class","axis axisX").attr("transform","translate(0,"+bottom+")");xAxis.call(xRules);labels=self._gScale.selectAll(".axisX g")[0];if(labels.length>self._width/80){labels.sort(function(a,b){var r=/translate\(([^,)]+)/;a=a.getAttribute("transform").match(r);b=b.getAttribute("transform").match(r);return parseFloat(a[1],10)-parseFloat(b[1],10)});d3.selectAll(labels).filter(function(d,i){return i%(Math.ceil(labels.length/xTicks)+1)}).remove()}yRules=d3.svg.axis().scale(self.yScale).ticks(yTicks).tickSize(-self._width-o.axisPaddingRight-o.axisPaddingLeft).tickFormat(o.tickFormatY).orient("left");yAxis=self._gScale.selectAll("g.axisY").data(emptyData);yAxis.enter().append("g").attr("class","axis axisY").attr("transform","translate(0,0)");t.selectAll("g.axisY").call(yRules);zLine=self._gScale.selectAll("g.axisZero").data([[]]);zLine.enter().append("g").attr("class","axisZero");zLinePath=zLine.selectAll("line").data([[]]);zLinePath.enter().append("line").attr("x1",0).attr("x2",self._width+o.axisPaddingLeft+o.axisPaddingRight).attr("y1",self.yZero).attr("y2",self.yZero);zLinePath.transition().duration(o.timing).attr("y1",self.yZero).attr("y2",self.yZero)},_updateScale:function(){var self=this,_unionData=function(){return _.union(self._mainData,self._compData)},scaleData=_unionData(),vis=self._vis,scale,min;delete self.xScale;delete self.yScale;delete self.yZero;if(vis.hasOwnProperty("preUpdateScale")){vis.preUpdateScale(self,scaleData,self._mainData,self._compData)}scaleData=_unionData();scale=_scales.xy(self,scaleData,self._xScaleType,self._yScaleType);self.xScale=scale.x;self.yScale=scale.y;min=self.yScale.domain()[0];self.yZero=min>0?self.yScale(min):self.yScale(0);if(vis.hasOwnProperty("postUpdateScale")){vis.postUpdateScale(self,scaleData,self._mainData,self._compData)}},_enter:function(vis,storage,data,className){var self=this,callbacks={click:self._options.click,mouseover:self._options.mouseover,mouseout:self._options.mouseout};self._checkVisMethod(vis,"enter");vis.enter(self,storage,className,data,callbacks)},_update:function(vis,storage){var self=this;self._checkVisMethod(vis,"update");vis.update(self,storage,self._options.timing)},_exit:function(vis,storage){var self=this;self._checkVisMethod(vis,"exit");vis.exit(self,storage,self._options.timing)},_destroy:function(vis,storage){var self=this;self._checkVisMethod(vis,"destroy");try{vis.destroy(self,storage,self._options.timing)}catch(e){}},_mainStorage:{},_compStorage:{},_draw:function(){var self=this,o=self._options,comp,compKeys;self._noData=_.flatten(_.pluck(self._mainData,"data").concat(_.pluck(self._compData,"data"))).length===0;self._updateScale();self._drawAxes();self._enter(self._vis,self._mainStorage,self._mainData,".main");self._exit(self._vis,self._mainStorage);self._update(self._vis,self._mainStorage);comp=_.chain(self._compData).groupBy(function(d){return d.type});compKeys=comp.keys();_.each(self._compStorage,function(d,key){if(-1===compKeys.indexOf(key).value()){var vis=_vis[key];self._enter(vis,d,[],".comp."+key.replace(/\W+/g,""));self._exit(vis,d)}});comp.each(function(d,key){var vis=_vis[key],storage;if(!self._compStorage.hasOwnProperty(key)){self._compStorage[key]={}}storage=self._compStorage[key];self._enter(vis,storage,d,".comp."+key.replace(/\W+/g,""));self._exit(vis,storage);self._update(vis,storage)});if(self._noData){o.empty(self,self._selector,self._mainData)}else{o.notempty(self,self._selector)}},_checkVisMethod:function(vis,method){var self=this;if(!vis[method]){throw'Required method "'+method+'" not found on vis type "'+self._type+'".'}}});if(typeof define==="function"&&define.amd&&typeof define.amd==="object"){define(function(){return xChart});return}window.xChart=xChart})();

