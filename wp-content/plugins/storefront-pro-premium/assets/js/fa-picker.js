! function ( a, b ) {
	function c( a, b, c ) {
		return [
			parseFloat( a[0] ) * (
				n.test( a[0] ) ? b / 100 : 1
			),
			parseFloat( a[1] ) * (
				n.test( a[1] ) ? c / 100 : 1
			)
		]
	}

	function d( b, c ) {return parseInt( a.css( b, c ), 10 ) || 0}

	function e( b ) {
		var c = b[0];
		return 9 === c.nodeType ? {width: b.width(), height: b.height(), offset: {top: 0, left: 0}} : a.isWindow( c ) ? {width: b.width(), height: b.height(), offset: {top: b.scrollTop(), left: b.scrollLeft()}} : c.preventDefault ? {width: 0, height: 0, offset: {top: c.pageY, left: c.pageX}} : {width: b.outerWidth(), height: b.outerHeight(), offset: b.offset()}
	}

	a.ui = a.ui || {};
	var f, g = Math.max, h = Math.abs, i = Math.round, j = /left|center|right/, k = /top|center|bottom/,
			l                                                                         = /[\+\-]\d+(\.[\d]+)?%?/, m = /^\w+/, n = /%$/, o = a.fn.pos;
	a.pos = {
		scrollbarWidth  : function () {
			if ( f !== b ) {
				return f;
			}
			var c, d,
					e = a( "<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>" ),
					g = e.children()[0];
			return a( "body" ).append( e ), c = g.offsetWidth, e.css( "overflow", "scroll" ), d = g.offsetWidth, c === d && (
				d = e[0].clientWidth
			), e.remove(), f = c - d
		}, getScrollInfo: function ( b ) {
			var c = b.isWindow || b.isDocument ? "" : b.element.css( "overflow-x" ),
					d = b.isWindow || b.isDocument ? "" : b.element.css( "overflow-y" ),
					e = "scroll" === c || "auto" === c && b.width < b.element[0].scrollWidth,
					f = "scroll" === d || "auto" === d && b.height < b.element[0].scrollHeight;
			return {width: f ? a.pos.scrollbarWidth() : 0, height: e ? a.pos.scrollbarWidth() : 0}
		}, getWithinInfo: function ( b ) {
			var c = a( b || window ), d = a.isWindow( c[0] ), e = ! ! c[0] && 9 === c[0].nodeType;
			return {element: c, isWindow: d, isDocument: e, offset: c.offset() || {left: 0, top: 0}, scrollLeft: c.scrollLeft(), scrollTop: c.scrollTop(), width: d ? c.width() : c.outerWidth(), height: d ? c.height() : c.outerHeight()}
		}
	}, a.fn.pos = function ( b ) {
		if ( ! b || ! b.of ) {
			return o.apply( this, arguments );
		}
		b = a.extend( {}, b );
		var f, n, p, q, r, s, t = a( b.of ), u = a.pos.getWithinInfo( b.within ), v = a.pos.getScrollInfo( u ), w = (
			b.collision || "flip"
		).split( " " ), x       = {};
		return s = e( t ), t[0].preventDefault && (
			b.at = "left top"
		), n = s.width, p = s.height, q = s.offset, r = a.extend( {}, q ), a.each( ["my", "at"], function () {
			var a, c, d = (
				b[this] || ""
			).split( " " );
			1 === d.length && (
				d = j.test( d[0] ) ? d.concat( ["center"] ) : k.test( d[0] ) ? ["center"].concat( d ) : ["center", "center"]
			), d[0] = j.test( d[0] ) ? d[0] : "center", d[1] = k.test( d[1] ) ? d[1] : "center", a = l.exec( d[0] ), c = l.exec( d[1] ), x[this] = [
				a ? a[0] : 0,
				c ? c[0] : 0
			], b[this] = [m.exec( d[0] )[0], m.exec( d[1] )[0]]
		} ), 1 === w.length && (
			w[1] = w[0]
		), "right" === b.at[0] ? r.left += n : "center" === b.at[0] && (
			r.left += n / 2
		), "bottom" === b.at[1] ? r.top += p : "center" === b.at[1] && (
			r.top += p / 2
		), f = c( x.at, n, p ), r.left += f[0], r.top += f[1], this.each( function () {
			var e, j, k = a( this ), l = k.outerWidth(), m = k.outerHeight(), o = d( this, "marginLeft" ),
					s                                                               = d( this, "marginTop" ), y                                   = l + o + d( this, "marginRight" ) + v.width,
					z                                                               = m + s + d( this, "marginBottom" ) + v.height, A = a.extend( {}, r ),
					B                                                               = c( x.my, k.outerWidth(), k.outerHeight() );
			"right" === b.my[0] ? A.left -= l : "center" === b.my[0] && (
				A.left -= l / 2
			), "bottom" === b.my[1] ? A.top -= m : "center" === b.my[1] && (
				A.top -= m / 2
			), A.left += B[0], A.top += B[1], a.support.offsetFractions || (
				A.left = i( A.left ), A.top = i( A.top )
			), e = {marginLeft: o, marginTop: s}, a.each( [
				"left",
				"top"
			], function ( c, d ) {
				a.ui.pos[w[c]] && a.ui.pos[w[c]][d]( A, {
					targetWidth: n, targetHeight: p, elemWidth: l, elemHeight: m, collisionPosition: e, collisionWidth: y, collisionHeight: z, offset: [
						f[0] + B[0],
						f[1] + B[1]
					], my      : b.my, at: b.at, within: u, elem: k
				} )
			} ), b.using && (
				j = function ( a ) {
					var c = q.left - A.left, d = c + n - l, e = q.top - A.top, f = e + p - m,
							i                                                        = {target: {element: t, left: q.left, top: q.top, width: n, height: p}, element: {element: k, left: A.left, top: A.top, width: l, height: m}, horizontal: 0 > d ? "left" : c > 0 ? "right" : "center", vertical: 0 > f ? "top" : e > 0 ? "bottom" : "middle"};
					l > n && h( c + d ) < n && (
						i.horizontal = "center"
					), m > p && h( e + f ) < p && (
						i.vertical = "middle"
					), g( h( c ), h( d ) ) > g( h( e ), h( f ) ) ? i.important = "horizontal" : i.important = "vertical", b.using.call( this, a, i )
				}
			), k.offset( a.extend( A, {using: j} ) )
		} )
	}, a.ui.pos = {
		_trigger  : function ( a, b, c, d ) {b.elem && b.elem.trigger( {type: c, position: a, positionData: b, triggered: d} )}, fit: {
			left  : function ( b, c ) {
				a.ui.pos._trigger( b, c, "posCollide", "fitLeft" );
				var d, e = c.within, f = e.isWindow ? e.scrollLeft : e.offset.left, h = e.width,
						i                                                                 = b.left - c.collisionPosition.marginLeft, j                    = f - i, k = i + c.collisionWidth - h - f;
				c.collisionWidth > h ? j > 0 && 0 >= k ? (
					d = b.left + j + c.collisionWidth - h - f, b.left += j - d
				) : k > 0 && 0 >= j ? b.left = f : j > k ? b.left = f + h - c.collisionWidth : b.left = f : j > 0 ? b.left += j : k > 0 ? b.left -= k : b.left = g( b.left - i, b.left ), a.ui.pos._trigger( b, c, "posCollided", "fitLeft" )
			}, top: function ( b, c ) {
				a.ui.pos._trigger( b, c, "posCollide", "fitTop" );
				var d, e = c.within, f = e.isWindow ? e.scrollTop : e.offset.top, h = c.within.height,
						i                                                               = b.top - c.collisionPosition.marginTop, j                    = f - i, k = i + c.collisionHeight - h - f;
				c.collisionHeight > h ? j > 0 && 0 >= k ? (
					d = b.top + j + c.collisionHeight - h - f, b.top += j - d
				) : k > 0 && 0 >= j ? b.top = f : j > k ? b.top = f + h - c.collisionHeight : b.top = f : j > 0 ? b.top += j : k > 0 ? b.top -= k : b.top = g( b.top - i, b.top ), a.ui.pos._trigger( b, c, "posCollided", "fitTop" )
			}
		}, flip   : {
			left  : function ( b, c ) {
				a.ui.pos._trigger( b, c, "posCollide", "flipLeft" );
				var d, e, f                                                                                       = c.within, g                                                                         = f.offset.left + f.scrollLeft, i = f.width,
						j = f.isWindow ? f.scrollLeft : f.offset.left, k = b.left - c.collisionPosition.marginLeft, l = k - j,
						m                                                                                             = k + c.collisionWidth - i - j,
						n                                                                                             = "left" === c.my[0] ? - c.elemWidth : "right" === c.my[0] ? c.elemWidth : 0,
						o                                                                                             = "left" === c.at[0] ? c.targetWidth : "right" === c.at[0] ? - c.targetWidth : 0,
						p                                                                                             = - 2 * c.offset[0];
				0 > l ? (
					d = b.left + n + o + p + c.collisionWidth - i - g, (
																															 0 > d || d < h( l )
																														 ) && (
																															 b.left += n + o + p
																														 )
				) : m > 0 && (
					e = b.left - c.collisionPosition.marginLeft + n + o + p - j, (
							e > 0 || h( e ) < m
						) && (
							b.left += n + o + p
						)
				), a.ui.pos._trigger( b, c, "posCollided", "flipLeft" )
			}, top: function ( b, c ) {
				a.ui.pos._trigger( b, c, "posCollide", "flipTop" );
				var d, e, f                                                                                   = c.within, g = f.offset.top + f.scrollTop, i = f.height,
						j = f.isWindow ? f.scrollTop : f.offset.top, k = b.top - c.collisionPosition.marginTop, l = k - j,
						m                                                                                         = k + c.collisionHeight - i - j, n = "top" === c.my[1],
						o                                                                                         = n ? - c.elemHeight : "bottom" === c.my[1] ? c.elemHeight : 0,
						p                                                                                         = "top" === c.at[1] ? c.targetHeight : "bottom" === c.at[1] ? - c.targetHeight : 0,
						q                                                                                         = - 2 * c.offset[1];
				0 > l ? (
					e = b.top + o + p + q + c.collisionHeight - i - g, b.top + o + p + q > l && (
						0 > e || e < h( l )
					) && (
																															 b.top += o + p + q
																														 )
				) : m > 0 && (
					d = b.top - c.collisionPosition.marginTop + o + p + q - j, b.top + o + p + q > m && (
							d > 0 || h( d ) < m
						) && (
							b.top += o + p + q
						)
				), a.ui.pos._trigger( b, c, "posCollided", "flipTop" )
			}
		}, flipfit: {left: function () {a.ui.pos.flip.left.apply( this, arguments ), a.ui.pos.fit.left.apply( this, arguments )}, top: function () {a.ui.pos.flip.top.apply( this, arguments ), a.ui.pos.fit.top.apply( this, arguments )}}
	}, function () {
		var b, c, d, e, f, g = document.getElementsByTagName( "body" )[0], h = document.createElement( "div" );
		b = document.createElement( g ? "div" : "body" ), d = {visibility: "hidden", width: 0, height: 0, border: 0, margin: 0, background: "none"}, g && a.extend( d, {position: "absolute", left: "-1000px", top: "-1000px"} );
		for ( f in d ) {
			b.style[f] = d[f];
		}
		b.appendChild( h ), c = g || document.documentElement, c.insertBefore( b, c.firstChild ), h.style.cssText = "position: absolute; left: 10.7432222px;", e = a( h ).offset().left, a.support.offsetFractions = e > 10 && 11 > e, b.innerHTML = "", c.removeChild( b )
	}()
}( jQuery ), function ( a ) {
	"use strict";
	"function" == typeof define && define.amd ? define( ["jquery"], a ) : window.jQuery && ! window.jQuery.fn.iconpicker && a( window.jQuery )
}( function ( a ) {
	"use strict";
	var b = {isEmpty: function ( a ) {return a === ! 1 || "" === a || null === a || void 0 === a}, isEmptyObject: function ( a ) {return this.isEmpty( a ) === ! 0 || 0 === a.length}, isElement: function ( b ) {return a( b ).length > 0}, isString: function ( a ) {return "string" == typeof a || a instanceof String}, isArray: function ( b ) {return a.isArray( b )}, inArray: function ( b, c ) {return - 1 !== a.inArray( b, c )}, throwError: function ( a ) {throw"Font Awesome Icon Picker Exception: " + a}},
			c = function ( d, e ) {
				this._id = c._idCounter ++, this.element = a( d ).addClass( "iconpicker-element" ), this._trigger( "iconpickerCreate" ), this.options = a.extend( {}, c.defaultOptions, this.element.data(), e ), this.options.templates = a.extend( {}, c.defaultOptions.templates, this.options.templates ), this.options.originalPlacement = this.options.placement, this.container = b.isElement( this.options.container ) ? a( this.options.container ) : ! 1, this.container === ! 1 && (
					this.element.is( ".dropdown-toggle" ) ? this.container = a( "~ .dropdown-menu:first", this.element ) : this.container = this.element.is( "input,textarea,button,.btn" ) ? this.element.parent() : this.element
				), this.container.addClass( "iconpicker-container" ), this.isDropdownMenu() && (
					this.options.templates.search = ! 1, this.options.templates.buttons = ! 1, this.options.placement = "inline"
				), this.input = this.element.is( "input,textarea" ) ? this.element.addClass( "iconpicker-input" ) : ! 1, this.input === ! 1 && (
					this.input = this.container.find( this.options.input ), this.input.is( "input,textarea" ) || (
						this.input = ! 1
					)
				), this.component = this.isDropdownMenu() ? this.container.parent().find( this.options.component ) : this.container.find( this.options.component ), 0 === this.component.length ? this.component = ! 1 : this.component.find( "i" ).addClass( "iconpicker-component" ), this._createPopover(), this._createIconpicker(), 0 === this.getAcceptButton().length && (
					this.options.mustAccept = ! 1
				), this.isInputGroup() ? this.container.parent().append( this.popover ) : this.container.append( this.popover ), this._bindElementEvents(), this._bindWindowEvents(), this.update( this.options.selected ), this.isInline() && this.show(), this._trigger( "iconpickerCreated" )
			};
	c._idCounter = 0, c.defaultOptions = {title: ! 1, selected: ! 1, defaultValue: ! 1, placement: "bottom", collision: "none", animation: ! 0, hideOnSelect: ! 1, showFooter: ! 1, searchInFooter: ! 1, mustAccept: ! 1, selectedCustomClass: "bg-primary", icons: [], fullClassFormatter: function ( a ) {return "" + a}, input: "input,.iconpicker-input", inputSearch: ! 1, container: ! 1, component: ".input-group-addon,.iconpicker-component", templates: {popover: '<div class="iconpicker-popover popover"><div class="arrow"></div><div class="popover-title"></div><div class="popover-content"></div></div>', footer: '<div class="popover-footer"></div>', buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">' + sfpFAPickerL10n.cancel + '</button> <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">' + sfpFAPickerL10n.accept + '</button>', search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />', iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>', iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>'}}, c.batch = function ( b, c ) {
		var d = Array.prototype.slice.call( arguments, 2 );
		return a( b ).each( function () {
			var b = a( this ).data( "iconpicker" );
			b && b[c].apply( b, d )
		} )
	}, c.prototype = {
		constructor                : c, options: {}, _id: 0, _trigger: function ( b, c ) {c = c || {}, this.element.trigger( a.extend( {type: b, iconpickerInstance: this}, c ) )}, _createPopover: function () {
			this.popover = a( this.options.templates.popover );
			var c = this.popover.find( ".popover-title" );
			if ( this.options.title && c.append( a( '<div class="popover-title-text">' + this.options.title + "</div>" ) ), this.hasSeparatedSearchInput() && ! this.options.searchInFooter ? c.append( this.options.templates.search ) : this.options.title || c.remove(), this.options.showFooter && ! b.isEmpty( this.options.templates.footer ) ) {
				var d = a( this.options.templates.footer );
				this.hasSeparatedSearchInput() && this.options.searchInFooter && d.append( a( this.options.templates.search ) ), b.isEmpty( this.options.templates.buttons ) || d.append( a( this.options.templates.buttons ) ), this.popover.append( d )
			}
			return this.options.animation === ! 0 && this.popover.addClass( "fade" ), this.popover
		}, _createIconpicker       : function () {
			var b = this;
			this.iconpicker = a( this.options.templates.iconpicker );
			var c = function ( c ) {
				var d = a( this );
				return d.is( "i" ) && (
					d = d.parent()
				), b._trigger( "iconpickerSelect", {iconpickerItem: d, iconpickerValue: b.iconpickerValue} ), b.options.mustAccept === ! 1 ? (
					b.update( d.data( "iconpickerValue" ) ), b._trigger( "iconpickerSelected", {iconpickerItem: this, iconpickerValue: b.iconpickerValue} )
				) : b.update( d.data( "iconpickerValue" ), ! 0 ), b.options.hideOnSelect && b.options.mustAccept === ! 1 && b.hide(), c.preventDefault(), ! 1
			};
			for ( var d in this.options.icons ) {
				var e = a( this.options.templates.iconpickerItem );
				e.find( "i" ).addClass( this.options.fullClassFormatter( this.options.icons[d] ) ), e.data( "iconpickerValue", this.options.icons[d] ).on( "click.iconpicker", c ), this.iconpicker.find( ".iconpicker-items" ).append( e.attr( "title", "." + this.options.icons[d] ) )
			}
			return this.popover.find( ".popover-content" ).append( this.iconpicker ), this.iconpicker
		}, _isEventInsideIconpicker: function ( b ) {
			var c = a( b.target );
			return c.hasClass( "iconpicker-element" ) && (
						 ! c.hasClass( "iconpicker-element" ) || c.is( this.element )
			) || 0 !== c.parents( ".iconpicker-popover" ).length ? ! 0 : ! 1
		}, _bindElementEvents      : function () {
			var c = this;
			this.getSearchInput().on( "keyup.iconpicker", function () {c.filter( a( this ).val().toLowerCase() )} ), this.getAcceptButton().on( "click.iconpicker", function () {
				var a = c.iconpicker.find( ".iconpicker-selected" ).get( 0 );
				c.update( c.iconpickerValue ), c._trigger( "iconpickerSelected", {iconpickerItem: a, iconpickerValue: c.iconpickerValue} ), c.isInline() || c.hide()
			} ), this.getCancelButton().on( "click.iconpicker", function () {c.isInline() || c.hide()} ), this.element.on( "focus.iconpicker", function ( a ) {c.show(), a.stopPropagation()} ), this.hasComponent() && this.component.on( "click.iconpicker", function () {c.toggle()} ), this.hasInput() && this.input.on( "keyup.iconpicker", function ( d ) {
				b.inArray( d.keyCode, [
					38,
					40,
					37,
					39,
					16,
					17,
					18,
					9,
					8,
					91,
					93,
					20,
					46,
					186,
					190,
					46,
					78,
					188,
					44,
					86
				] ) ? c._updateFormGroupStatus( c.getValid( this.value ) !== ! 1 ) : c.update(), c.options.inputSearch === ! 0 && c.filter( a( this ).val().toLowerCase() )
			} )
		}, _bindWindowEvents       : function () {
			var b = a( window.document ), c = this, d = ".iconpicker.inst" + this._id;
			return a( window ).on( "resize.iconpicker" + d + " orientationchange.iconpicker" + d, function ( a ) {c.popover.hasClass( "in" ) && c.updatePlacement()} ), c.isInline() || b.on( "mouseup" + d, function ( a ) {return c._isEventInsideIconpicker( a ) || c.isInline() || c.hide(), a.stopPropagation(), a.preventDefault(), ! 1} ), ! 1
		}, _unbindElementEvents    : function () {this.popover.off( ".iconpicker" ), this.element.off( ".iconpicker" ), this.hasInput() && this.input.off( ".iconpicker" ), this.hasComponent() && this.component.off( ".iconpicker" ), this.hasContainer() && this.container.off( ".iconpicker" )}, _unbindWindowEvents: function () {a( window ).off( ".iconpicker.inst" + this._id ), a( window.document ).off( ".iconpicker.inst" + this._id )}, updatePlacement: function ( b, c ) {
			b = b || this.options.placement, this.options.placement = b, c = c || this.options.collision, c = c === ! 0 ? "flip" : c;
			var d = {at: "right bottom", my: "right top", of: this.hasInput() && ! this.isInputGroup() ? this.input : this.container, collision: c === ! 0 ? "flip" : c, within: window};
			if ( this.popover.removeClass( "inline topLeftCorner topLeft top topRight topRightCorner rightTop right rightBottom bottomRight bottomRightCorner bottom bottomLeft bottomLeftCorner leftBottom left leftTop" ), "object" == typeof b ) {
				return this.popover.pos( a.extend( {}, d, b ) );
			}
			switch ( b ) {
				case"inline":
					d = ! 1;
					break;
				case"topLeftCorner":
					d.my = "right bottom", d.at = "left top";
					break;
				case"topLeft":
					d.my = "left bottom", d.at = "left top";
					break;
				case"top":
					d.my = "center bottom", d.at = "center top";
					break;
				case"topRight":
					d.my = "right bottom", d.at = "right top";
					break;
				case"topRightCorner":
					d.my = "left bottom", d.at = "right top";
					break;
				case"rightTop":
					d.my = "left bottom", d.at = "right center";
					break;
				case"right":
					d.my = "left center", d.at = "right center";
					break;
				case"rightBottom":
					d.my = "left top", d.at = "right center";
					break;
				case"bottomRightCorner":
					d.my = "left top", d.at = "right bottom";
					break;
				case"bottomRight":
					d.my = "right top", d.at = "right bottom";
					break;
				case"bottom":
					d.my = "center top", d.at = "center bottom";
					break;
				case"bottomLeft":
					d.my = "left top", d.at = "left bottom";
					break;
				case"bottomLeftCorner":
					d.my = "right top", d.at = "left bottom";
					break;
				case"leftBottom":
					d.my = "right top", d.at = "left center";
					break;
				case"left":
					d.my = "right center", d.at = "left center";
					break;
				case"leftTop":
					d.my = "right bottom", d.at = "left center";
					break;
				default:
					return ! 1
			}
			return this.popover.css( {display: "inline" === this.options.placement ? "" : "block"} ), d !== ! 1 ? this.popover.pos( d ).css( "maxWidth", a( window ).width() - this.container.offset().left - 5 ) : this.popover.css( {top: "auto", right: "auto", bottom: "auto", left: "auto", maxWidth: "none"} ), this.popover.addClass( this.options.placement ), ! 0
		}, _updateComponents       : function () {
			if ( this.iconpicker.find( ".iconpicker-item.iconpicker-selected" ).removeClass( "iconpicker-selected " + this.options.selectedCustomClass ), this.iconpickerValue && this.iconpicker.find( "." + this.options.fullClassFormatter( this.iconpickerValue ).replace( / /g, "." ) ).parent().addClass( "iconpicker-selected " + this.options.selectedCustomClass ), this.hasComponent() ) {
				var a = this.component.find( "i" );
				a.length > 0 ? a.attr( "class", this.options.fullClassFormatter( this.iconpickerValue ) ) : this.component.html( this.getHtml() )
			}
		}, _updateFormGroupStatus  : function ( a ) {
			return this.hasInput() ? (
				a !== ! 1 ? this.input.parents( ".form-group:first" ).removeClass( "has-error" ) : this.input.parents( ".form-group:first" ).addClass( "has-error" ), ! 0
			) : ! 1
		}, getValid                : function ( c ) {
			b.isString( c ) || (
				c = ""
			);
			var d = "" === c;
			return c = a.trim( c ), b.inArray( c, this.options.icons ) || d ? c : ! 1
		}, setValue                : function ( a ) {
			var b = this.getValid( a );
			return b !== ! 1 ? (
				this.iconpickerValue = b, this._trigger( "iconpickerSetValue", {iconpickerValue: b} ), this.iconpickerValue
			) : (
				this._trigger( "iconpickerInvalid", {iconpickerValue: a} ), ! 1
			)
		}, getHtml                 : function () {return '<i class="' + this.options.fullClassFormatter( this.iconpickerValue ) + '"></i>'}, setSourceValue: function ( a ) {
			return a = this.setValue( a ), a !== ! 1 && "" !== a && (
				this.hasInput() ? this.input.val( this.iconpickerValue ) : this.element.data( "iconpickerValue", this.iconpickerValue ), this._trigger( "iconpickerSetSourceValue", {iconpickerValue: a} )
			), a
		}, getSourceValue          : function ( a ) {
			a = a || this.options.defaultValue;
			var b = a;
			return b = this.hasInput() ? this.input.val() : this.element.data( "iconpickerValue" ), (
																																																void 0 === b || "" === b || null === b || b === ! 1
																																															) && (
																																																b = a
																																															), b
		}, hasInput                : function () {return this.input !== ! 1}, isInputSearch: function () {return this.hasInput() && this.options.inputSearch === ! 0}, isInputGroup: function () {return this.container.is( ".input-group" )}, isDropdownMenu: function () {return this.container.is( ".dropdown-menu" )}, hasSeparatedSearchInput: function () {return this.options.templates.search !== ! 1 && ! this.isInputSearch()}, hasComponent: function () {return this.component !== ! 1}, hasContainer: function () {return this.container !== ! 1}, getAcceptButton: function () {return this.popover.find( ".iconpicker-btn-accept" )}, getCancelButton: function () {return this.popover.find( ".iconpicker-btn-cancel" )}, getSearchInput: function () {return this.popover.find( ".iconpicker-search" )}, filter: function ( c ) {
			if ( b.isEmpty( c ) ) {
				return this.iconpicker.find( ".iconpicker-item" ).show(), a( ! 1 );
			}
			var d = [];
			return this.iconpicker.find( ".iconpicker-item" ).each( function () {
				var b = a( this ), e = b.attr( "title" ).toLowerCase(), f = ! 1;
				try {
					f = new RegExp( c, "g" )
				} catch ( g ) {
					f = ! 1
				}
				f !== ! 1 && e.match( f ) ? (
					d.push( b ), b.show()
				) : b.hide()
			} ), d
		}, show                    : function () {
			return this.popover.hasClass( "in" ) ? ! 1 : (
				a.iconpicker.batch( a( ".iconpicker-popover.in:not(.inline)" ).not( this.popover ), "hide" ), this._trigger( "iconpickerShow" ), this.updatePlacement(), this.popover.addClass( "in" ), void setTimeout( a.proxy( function () {this.popover.css( "display", this.isInline() ? "" : "block" ), this._trigger( "iconpickerShown" )}, this ), this.options.animation ? 300 : 1 )
			)
		}, hide                    : function () {
			return this.popover.hasClass( "in" ) ? (
				this._trigger( "iconpickerHide" ), this.popover.removeClass( "in" ), void setTimeout( a.proxy( function () {this.popover.css( "display", "none" ), this.getSearchInput().val( "" ), this.filter( "" ), this._trigger( "iconpickerHidden" )}, this ), this.options.animation ? 300 : 1 )
			) : ! 1
		}, toggle                  : function () {this.popover.is( ":visible" ) ? this.hide() : this.show( ! 0 )}, update: function ( a, b ) {
			return a = a ? a : this.getSourceValue( this.iconpickerValue ), this._trigger( "iconpickerUpdate" ), b === ! 0 ? a = this.setValue( a ) : (
				a = this.setSourceValue( a ), this._updateFormGroupStatus( a !== ! 1 )
			), a !== ! 1 && this._updateComponents(), this._trigger( "iconpickerUpdated" ), a
		}, destroy                 : function () {this._trigger( "iconpickerDestroy" ), this.element.removeData( "iconpicker" ).removeData( "iconpickerValue" ).removeClass( "iconpicker-element" ), this._unbindElementEvents(), this._unbindWindowEvents(), a( this.popover ).remove(), this._trigger( "iconpickerDestroyed" )}, disable: function () {
			return this.hasInput() ? (
				this.input.prop( "disabled", ! 0 ), ! 0
			) : ! 1
		}, enable                  : function () {
			return this.hasInput() ? (
				this.input.prop( "disabled", ! 1 ), ! 0
			) : ! 1
		}, isDisabled              : function () {return this.hasInput() ? this.input.prop( "disabled" ) === ! 0 : ! 1}, isInline: function () {return "inline" === this.options.placement || this.popover.hasClass( "inline" )}
	}, a.iconpicker = c, a.fn.iconpicker = function ( b ) {
		return this.each( function () {
			var d = a( this );
			d.data( "iconpicker" ) || d.data( "iconpicker", new c( this, "object" == typeof b ? b : {} ) )
		} )
	}, c.defaultOptions.icons = [
		'fas fa-ad',
		'fas fa-address-book',
		'fas fa-address-card',
		'fas fa-adjust',
		'fas fa-air-freshener',
		'fas fa-align-center',
		'fas fa-align-justify',
		'fas fa-align-left',
		'fas fa-align-right',
		'fas fa-allergies',
		'fas fa-ambulance',
		'fas fa-american-sign-language-interpreting',
		'fas fa-anchor',
		'fas fa-angle-double-down',
		'fas fa-angle-double-left',
		'fas fa-angle-double-right',
		'fas fa-angle-double-up',
		'fas fa-angle-down',
		'fas fa-angle-left',
		'fas fa-angle-right',
		'fas fa-angle-up',
		'fas fa-angry',
		'fas fa-ankh',
		'fas fa-apple-alt',
		'fas fa-archive',
		'fas fa-archway',
		'fas fa-arrow-alt-circle-down',
		'fas fa-arrow-alt-circle-left',
		'fas fa-arrow-alt-circle-right',
		'fas fa-arrow-alt-circle-up',
		'fas fa-arrow-circle-down',
		'fas fa-arrow-circle-left',
		'fas fa-arrow-circle-right',
		'fas fa-arrow-circle-up',
		'fas fa-arrow-down',
		'fas fa-arrow-left',
		'fas fa-arrow-right',
		'fas fa-arrow-up',
		'fas fa-arrows-alt',
		'fas fa-arrows-alt-h',
		'fas fa-arrows-alt-v',
		'fas fa-assistive-listening-systems',
		'fas fa-asterisk',
		'fas fa-at',
		'fas fa-atlas',
		'fas fa-atom',
		'fas fa-audio-description',
		'fas fa-award',
		'fas fa-backspace',
		'fas fa-backward',
		'fas fa-balance-scale',
		'fas fa-ban',
		'fas fa-band-aid',
		'fas fa-barcode',
		'fas fa-bars',
		'fas fa-baseball-ball',
		'fas fa-basketball-ball',
		'fas fa-bath',
		'fas fa-battery-empty',
		'fas fa-battery-full',
		'fas fa-battery-half',
		'fas fa-battery-quarter',
		'fas fa-battery-three-quarters',
		'fas fa-bed',
		'fas fa-beer',
		'fas fa-bell',
		'fas fa-bell-slash',
		'fas fa-bezier-curve',
		'fas fa-bible',
		'fas fa-bicycle',
		'fas fa-binoculars',
		'fas fa-birthday-cake',
		'fas fa-blender',
		'fas fa-blender-phone',
		'fas fa-blind',
		'fas fa-bold',
		'fas fa-bolt',
		'fas fa-bomb',
		'fas fa-bone',
		'fas fa-bong',
		'fas fa-book',
		'fas fa-book-dead',
		'fas fa-book-open',
		'fas fa-book-reader',
		'fas fa-bookmark',
		'fas fa-bowling-ball',
		'fas fa-box',
		'fas fa-box-open',
		'fas fa-boxes',
		'fas fa-braille',
		'fas fa-brain',
		'fas fa-briefcase',
		'fas fa-briefcase-medical',
		'fas fa-broadcast-tower',
		'fas fa-broom',
		'fas fa-brush',
		'fas fa-bug',
		'fas fa-building',
		'fas fa-bullhorn',
		'fas fa-bullseye',
		'fas fa-burn',
		'fas fa-bus',
		'fas fa-bus-alt',
		'fas fa-business-time',
		'fas fa-calculator',
		'fas fa-calendar',
		'fas fa-calendar-alt',
		'fas fa-calendar-check',
		'fas fa-calendar-minus',
		'fas fa-calendar-plus',
		'fas fa-calendar-times',
		'fas fa-camera',
		'fas fa-camera-retro',
		'fas fa-campground',
		'fas fa-cannabis',
		'fas fa-capsules',
		'fas fa-car',
		'fas fa-car-alt',
		'fas fa-car-battery',
		'fas fa-car-crash',
		'fas fa-car-side',
		'fas fa-caret-down',
		'fas fa-caret-left',
		'fas fa-caret-right',
		'fas fa-caret-square-down',
		'fas fa-caret-square-left',
		'fas fa-caret-square-right',
		'fas fa-caret-square-up',
		'fas fa-caret-up',
		'fas fa-cart-arrow-down',
		'fas fa-cart-plus',
		'fas fa-cat',
		'fas fa-certificate',
		'fas fa-chair',
		'fas fa-chalkboard',
		'fas fa-chalkboard-teacher',
		'fas fa-charging-station',
		'fas fa-chart-area',
		'fas fa-chart-bar',
		'fas fa-chart-line',
		'fas fa-chart-pie',
		'fas fa-check',
		'fas fa-check-circle',
		'fas fa-check-double',
		'fas fa-check-square',
		'fas fa-chess',
		'fas fa-chess-bishop',
		'fas fa-chess-board',
		'fas fa-chess-king',
		'fas fa-chess-knight',
		'fas fa-chess-pawn',
		'fas fa-chess-queen',
		'fas fa-chess-rook',
		'fas fa-chevron-circle-down',
		'fas fa-chevron-circle-left',
		'fas fa-chevron-circle-right',
		'fas fa-chevron-circle-up',
		'fas fa-chevron-down',
		'fas fa-chevron-left',
		'fas fa-chevron-right',
		'fas fa-chevron-up',
		'fas fa-child',
		'fas fa-church',
		'fas fa-circle',
		'fas fa-circle-notch',
		'fas fa-city',
		'fas fa-clipboard',
		'fas fa-clipboard-check',
		'fas fa-clipboard-list',
		'fas fa-clock',
		'fas fa-clone',
		'fas fa-closed-captioning',
		'fas fa-cloud',
		'fas fa-cloud-download-alt',
		'fas fa-cloud-meatball',
		'fas fa-cloud-moon',
		'fas fa-cloud-moon-rain',
		'fas fa-cloud-rain',
		'fas fa-cloud-showers-heavy',
		'fas fa-cloud-sun',
		'fas fa-cloud-sun-rain',
		'fas fa-cloud-upload-alt',
		'fas fa-cocktail',
		'fas fa-code',
		'fas fa-code-branch',
		'fas fa-coffee',
		'fas fa-cog',
		'fas fa-cogs',
		'fas fa-coins',
		'fas fa-columns',
		'fas fa-comment',
		'fas fa-comment-alt',
		'fas fa-comment-dollar',
		'fas fa-comment-dots',
		'fas fa-comment-slash',
		'fas fa-comments',
		'fas fa-comments-dollar',
		'fas fa-compact-disc',
		'fas fa-compass',
		'fas fa-compress',
		'fas fa-concierge-bell',
		'fas fa-cookie',
		'fas fa-cookie-bite',
		'fas fa-copy',
		'fas fa-copyright',
		'fas fa-couch',
		'fas fa-credit-card',
		'fas fa-crop',
		'fas fa-crop-alt',
		'fas fa-cross',
		'fas fa-crosshairs',
		'fas fa-crow',
		'fas fa-crown',
		'fas fa-cube',
		'fas fa-cubes',
		'fas fa-cut',
		'fas fa-database',
		'fas fa-deaf',
		'fas fa-democrat',
		'fas fa-desktop',
		'fas fa-dharmachakra',
		'fas fa-diagnoses',
		'fas fa-dice',
		'fas fa-dice-d20',
		'fas fa-dice-d6',
		'fas fa-dice-five',
		'fas fa-dice-four',
		'fas fa-dice-one',
		'fas fa-dice-six',
		'fas fa-dice-three',
		'fas fa-dice-two',
		'fas fa-digital-tachograph',
		'fas fa-directions',
		'fas fa-divide',
		'fas fa-dizzy',
		'fas fa-dna',
		'fas fa-dog',
		'fas fa-dollar-sign',
		'fas fa-dolly',
		'fas fa-dolly-flatbed',
		'fas fa-donate',
		'fas fa-door-closed',
		'fas fa-door-open',
		'fas fa-dot-circle',
		'fas fa-dove',
		'fas fa-download',
		'fas fa-drafting-compass',
		'fas fa-dragon',
		'fas fa-draw-polygon',
		'fas fa-drum',
		'fas fa-drum-steelpan',
		'fas fa-drumstick-bite',
		'fas fa-dumbbell',
		'fas fa-dungeon',
		'fas fa-edit',
		'fas fa-eject',
		'fas fa-ellipsis-h',
		'fas fa-ellipsis-v',
		'fas fa-envelope',
		'fas fa-envelope-open',
		'fas fa-envelope-open-text',
		'fas fa-envelope-square',
		'fas fa-equals',
		'fas fa-eraser',
		'fas fa-euro-sign',
		'fas fa-exchange-alt',
		'fas fa-exclamation',
		'fas fa-exclamation-circle',
		'fas fa-exclamation-triangle',
		'fas fa-expand',
		'fas fa-expand-arrows-alt',
		'fas fa-external-link-alt',
		'fas fa-external-link-square-alt',
		'fas fa-eye',
		'fas fa-eye-dropper',
		'fas fa-eye-slash',
		'fas fa-fast-backward',
		'fas fa-fast-forward',
		'fas fa-fax',
		'fas fa-feather',
		'fas fa-feather-alt',
		'fas fa-female',
		'fas fa-fighter-jet',
		'fas fa-file',
		'fas fa-file-alt',
		'fas fa-file-archive',
		'fas fa-file-audio',
		'fas fa-file-code',
		'fas fa-file-contract',
		'fas fa-file-csv',
		'fas fa-file-download',
		'fas fa-file-excel',
		'fas fa-file-export',
		'fas fa-file-image',
		'fas fa-file-import',
		'fas fa-file-invoice',
		'fas fa-file-invoice-dollar',
		'fas fa-file-medical',
		'fas fa-file-medical-alt',
		'fas fa-file-pdf',
		'fas fa-file-powerpoint',
		'fas fa-file-prescription',
		'fas fa-file-signature',
		'fas fa-file-upload',
		'fas fa-file-video',
		'fas fa-file-word',
		'fas fa-fill',
		'fas fa-fill-drip',
		'fas fa-film',
		'fas fa-filter',
		'fas fa-fingerprint',
		'fas fa-fire',
		'fas fa-fire-extinguisher',
		'fas fa-first-aid',
		'fas fa-fish',
		'fas fa-fist-raised',
		'fas fa-flag',
		'fas fa-flag-checkered',
		'fas fa-flag-usa',
		'fas fa-flask',
		'fas fa-flushed',
		'fas fa-folder',
		'fas fa-folder-minus',
		'fas fa-folder-open',
		'fas fa-folder-plus',
		'fas fa-font',
		'fas fa-font-awesome-logo-full',
		'fas fa-football-ball',
		'fas fa-forward',
		'fas fa-frog',
		'fas fa-frown',
		'fas fa-frown-open',
		'fas fa-funnel-dollar',
		'fas fa-futbol',
		'fas fa-gamepad',
		'fas fa-gas-pump',
		'fas fa-gavel',
		'fas fa-gem',
		'fas fa-genderless',
		'fas fa-ghost',
		'fas fa-gift',
		'fas fa-glass-martini',
		'fas fa-glass-martini-alt',
		'fas fa-glasses',
		'fas fa-globe',
		'fas fa-globe-africa',
		'fas fa-globe-americas',
		'fas fa-globe-asia',
		'fas fa-golf-ball',
		'fas fa-gopuram',
		'fas fa-graduation-cap',
		'fas fa-greater-than',
		'fas fa-greater-than-equal',
		'fas fa-grimace',
		'fas fa-grin',
		'fas fa-grin-alt',
		'fas fa-grin-beam',
		'fas fa-grin-beam-sweat',
		'fas fa-grin-hearts',
		'fas fa-grin-squint',
		'fas fa-grin-squint-tears',
		'fas fa-grin-stars',
		'fas fa-grin-tears',
		'fas fa-grin-tongue',
		'fas fa-grin-tongue-squint',
		'fas fa-grin-tongue-wink',
		'fas fa-grin-wink',
		'fas fa-grip-horizontal',
		'fas fa-grip-vertical',
		'fas fa-h-square',
		'fas fa-hammer',
		'fas fa-hamsa',
		'fas fa-hand-holding',
		'fas fa-hand-holding-heart',
		'fas fa-hand-holding-usd',
		'fas fa-hand-lizard',
		'fas fa-hand-paper',
		'fas fa-hand-peace',
		'fas fa-hand-point-down',
		'fas fa-hand-point-left',
		'fas fa-hand-point-right',
		'fas fa-hand-point-up',
		'fas fa-hand-pointer',
		'fas fa-hand-rock',
		'fas fa-hand-scissors',
		'fas fa-hand-spock',
		'fas fa-hands',
		'fas fa-hands-helping',
		'fas fa-handshake',
		'fas fa-hanukiah',
		'fas fa-hashtag',
		'fas fa-hat-wizard',
		'fas fa-haykal',
		'fas fa-hdd',
		'fas fa-heading',
		'fas fa-headphones',
		'fas fa-headphones-alt',
		'fas fa-headset',
		'fas fa-heart',
		'fas fa-heartbeat',
		'fas fa-helicopter',
		'fas fa-highlighter',
		'fas fa-hiking',
		'fas fa-hippo',
		'fas fa-history',
		'fas fa-hockey-puck',
		'fas fa-home',
		'fas fa-horse',
		'fas fa-hospital',
		'fas fa-hospital-alt',
		'fas fa-hospital-symbol',
		'fas fa-hot-tub',
		'fas fa-hotel',
		'fas fa-hourglass',
		'fas fa-hourglass-end',
		'fas fa-hourglass-half',
		'fas fa-hourglass-start',
		'fas fa-house-damage',
		'fas fa-hryvnia',
		'fas fa-i-cursor',
		'fas fa-id-badge',
		'fas fa-id-card',
		'fas fa-id-card-alt',
		'fas fa-image',
		'fas fa-images',
		'fas fa-inbox',
		'fas fa-indent',
		'fas fa-industry',
		'fas fa-infinity',
		'fas fa-info',
		'fas fa-info-circle',
		'fas fa-italic',
		'fas fa-jedi',
		'fas fa-joint',
		'fas fa-journal-whills',
		'fas fa-kaaba',
		'fas fa-key',
		'fas fa-keyboard',
		'fas fa-khanda',
		'fas fa-kiss',
		'fas fa-kiss-beam',
		'fas fa-kiss-wink-heart',
		'fas fa-kiwi-bird',
		'fas fa-landmark',
		'fas fa-language',
		'fas fa-laptop',
		'fas fa-laptop-code',
		'fas fa-laugh',
		'fas fa-laugh-beam',
		'fas fa-laugh-squint',
		'fas fa-laugh-wink',
		'fas fa-layer-group',
		'fas fa-leaf',
		'fas fa-lemon',
		'fas fa-less-than',
		'fas fa-less-than-equal',
		'fas fa-level-down-alt',
		'fas fa-level-up-alt',
		'fas fa-life-ring',
		'fas fa-lightbulb',
		'fas fa-link',
		'fas fa-lira-sign',
		'fas fa-list',
		'fas fa-list-alt',
		'fas fa-list-ol',
		'fas fa-list-ul',
		'fas fa-location-arrow',
		'fas fa-lock',
		'fas fa-lock-open',
		'fas fa-long-arrow-alt-down',
		'fas fa-long-arrow-alt-left',
		'fas fa-long-arrow-alt-right',
		'fas fa-long-arrow-alt-up',
		'fas fa-low-vision',
		'fas fa-luggage-cart',
		'fas fa-magic',
		'fas fa-magnet',
		'fas fa-mail-bulk',
		'fas fa-male',
		'fas fa-map',
		'fas fa-map-marked',
		'fas fa-map-marked-alt',
		'fas fa-map-marker',
		'fas fa-map-marker-alt',
		'fas fa-map-pin',
		'fas fa-map-signs',
		'fas fa-marker',
		'fas fa-mars',
		'fas fa-mars-double',
		'fas fa-mars-stroke',
		'fas fa-mars-stroke-h',
		'fas fa-mars-stroke-v',
		'fas fa-mask',
		'fas fa-medal',
		'fas fa-medkit',
		'fas fa-meh',
		'fas fa-meh-blank',
		'fas fa-meh-rolling-eyes',
		'fas fa-memory',
		'fas fa-menorah',
		'fas fa-mercury',
		'fas fa-meteor',
		'fas fa-microchip',
		'fas fa-microphone',
		'fas fa-microphone-alt',
		'fas fa-microphone-alt-slash',
		'fas fa-microphone-slash',
		'fas fa-microscope',
		'fas fa-minus',
		'fas fa-minus-circle',
		'fas fa-minus-square',
		'fas fa-mobile',
		'fas fa-mobile-alt',
		'fas fa-money-bill',
		'fas fa-money-bill-alt',
		'fas fa-money-bill-wave',
		'fas fa-money-bill-wave-alt',
		'fas fa-money-check',
		'fas fa-money-check-alt',
		'fas fa-monument',
		'fas fa-moon',
		'fas fa-mortar-pestle',
		'fas fa-mosque',
		'fas fa-motorcycle',
		'fas fa-mountain',
		'fas fa-mouse-pointer',
		'fas fa-music',
		'fas fa-network-wired',
		'fas fa-neuter',
		'fas fa-newspaper',
		'fas fa-not-equal',
		'fas fa-notes-medical',
		'fas fa-object-group',
		'fas fa-object-ungroup',
		'fas fa-oil-can',
		'fas fa-om',
		'fas fa-otter',
		'fas fa-outdent',
		'fas fa-paint-brush',
		'fas fa-paint-roller',
		'fas fa-palette',
		'fas fa-pallet',
		'fas fa-paper-plane',
		'fas fa-paperclip',
		'fas fa-parachute-box',
		'fas fa-paragraph',
		'fas fa-parking',
		'fas fa-passport',
		'fas fa-pastafarianism',
		'fas fa-paste',
		'fas fa-pause',
		'fas fa-pause-circle',
		'fas fa-paw',
		'fas fa-peace',
		'fas fa-pen',
		'fas fa-pen-alt',
		'fas fa-pen-fancy',
		'fas fa-pen-nib',
		'fas fa-pen-square',
		'fas fa-pencil-alt',
		'fas fa-pencil-ruler',
		'fas fa-people-carry',
		'fas fa-percent',
		'fas fa-percentage',
		'fas fa-person-booth',
		'fas fa-phone',
		'fas fa-phone-slash',
		'fas fa-phone-square',
		'fas fa-phone-volume',
		'fas fa-piggy-bank',
		'fas fa-pills',
		'fas fa-place-of-worship',
		'fas fa-plane',
		'fas fa-plane-arrival',
		'fas fa-plane-departure',
		'fas fa-play',
		'fas fa-play-circle',
		'fas fa-plug',
		'fas fa-plus',
		'fas fa-plus-circle',
		'fas fa-plus-square',
		'fas fa-podcast',
		'fas fa-poll',
		'fas fa-poll-h',
		'fas fa-poo',
		'fas fa-poo-storm',
		'fas fa-poop',
		'fas fa-portrait',
		'fas fa-pound-sign',
		'fas fa-power-off',
		'fas fa-pray',
		'fas fa-praying-hands',
		'fas fa-prescription',
		'fas fa-prescription-bottle',
		'fas fa-prescription-bottle-alt',
		'fas fa-print',
		'fas fa-procedures',
		'fas fa-project-diagram',
		'fas fa-puzzle-piece',
		'fas fa-qrcode',
		'fas fa-question',
		'fas fa-question-circle',
		'fas fa-quidditch',
		'fas fa-quote-left',
		'fas fa-quote-right',
		'fas fa-quran',
		'fas fa-rainbow',
		'fas fa-random',
		'fas fa-receipt',
		'fas fa-recycle',
		'fas fa-redo',
		'fas fa-redo-alt',
		'fas fa-registered',
		'fas fa-reply',
		'fas fa-reply-all',
		'fas fa-republican',
		'fas fa-retweet',
		'fas fa-ribbon',
		'fas fa-ring',
		'fas fa-road',
		'fas fa-robot',
		'fas fa-rocket',
		'fas fa-route',
		'fas fa-rss',
		'fas fa-rss-square',
		'fas fa-ruble-sign',
		'fas fa-ruler',
		'fas fa-ruler-combined',
		'fas fa-ruler-horizontal',
		'fas fa-ruler-vertical',
		'fas fa-running',
		'fas fa-rupee-sign',
		'fas fa-sad-cry',
		'fas fa-sad-tear',
		'fas fa-save',
		'fas fa-school',
		'fas fa-screwdriver',
		'fas fa-scroll',
		'fas fa-search',
		'fas fa-search-dollar',
		'fas fa-search-location',
		'fas fa-search-minus',
		'fas fa-search-plus',
		'fas fa-seedling',
		'fas fa-server',
		'fas fa-shapes',
		'fas fa-share',
		'fas fa-share-alt',
		'fas fa-share-alt-square',
		'fas fa-share-square',
		'fas fa-shekel-sign',
		'fas fa-shield-alt',
		'fas fa-ship',
		'fas fa-shipping-fast',
		'fas fa-shoe-prints',
		'fas fa-shopping-bag',
		'fas fa-shopping-basket',
		'fas fa-shopping-cart',
		'fas fa-shower',
		'fas fa-shuttle-van',
		'fas fa-sign',
		'fas fa-sign-in-alt',
		'fas fa-sign-language',
		'fas fa-sign-out-alt',
		'fas fa-signal',
		'fas fa-signature',
		'fas fa-sitemap',
		'fas fa-skull',
		'fas fa-skull-crossbones',
		'fas fa-slash',
		'fas fa-sliders-h',
		'fas fa-smile',
		'fas fa-smile-beam',
		'fas fa-smile-wink',
		'fas fa-smog',
		'fas fa-smoking',
		'fas fa-smoking-ban',
		'fas fa-snowflake',
		'fas fa-socks',
		'fas fa-solar-panel',
		'fas fa-sort',
		'fas fa-sort-alpha-down',
		'fas fa-sort-alpha-up',
		'fas fa-sort-amount-down',
		'fas fa-sort-amount-up',
		'fas fa-sort-down',
		'fas fa-sort-numeric-down',
		'fas fa-sort-numeric-up',
		'fas fa-sort-up',
		'fas fa-spa',
		'fas fa-space-shuttle',
		'fas fa-spider',
		'fas fa-spinner',
		'fas fa-splotch',
		'fas fa-spray-can',
		'fas fa-square',
		'fas fa-square-full',
		'fas fa-square-root-alt',
		'fas fa-stamp',
		'fas fa-star',
		'fas fa-star-and-crescent',
		'fas fa-star-half',
		'fas fa-star-half-alt',
		'fas fa-star-of-david',
		'fas fa-star-of-life',
		'fas fa-step-backward',
		'fas fa-step-forward',
		'fas fa-stethoscope',
		'fas fa-sticky-note',
		'fas fa-stop',
		'fas fa-stop-circle',
		'fas fa-stopwatch',
		'fas fa-store',
		'fas fa-store-alt',
		'fas fa-stream',
		'fas fa-street-view',
		'fas fa-strikethrough',
		'fas fa-stroopwafel',
		'fas fa-subscript',
		'fas fa-subway',
		'fas fa-suitcase',
		'fas fa-suitcase-rolling',
		'fas fa-sun',
		'fas fa-superscript',
		'fas fa-surprise',
		'fas fa-swatchbook',
		'fas fa-swimmer',
		'fas fa-swimming-pool',
		'fas fa-synagogue',
		'fas fa-sync',
		'fas fa-sync-alt',
		'fas fa-syringe',
		'fas fa-table',
		'fas fa-table-tennis',
		'fas fa-tablet',
		'fas fa-tablet-alt',
		'fas fa-tablets',
		'fas fa-tachometer-alt',
		'fas fa-tag',
		'fas fa-tags',
		'fas fa-tape',
		'fas fa-tasks',
		'fas fa-taxi',
		'fas fa-teeth',
		'fas fa-teeth-open',
		'fas fa-temperature-high',
		'fas fa-temperature-low',
		'fas fa-terminal',
		'fas fa-text-height',
		'fas fa-text-width',
		'fas fa-th',
		'fas fa-th-large',
		'fas fa-th-list',
		'fas fa-theater-masks',
		'fas fa-thermometer',
		'fas fa-thermometer-empty',
		'fas fa-thermometer-full',
		'fas fa-thermometer-half',
		'fas fa-thermometer-quarter',
		'fas fa-thermometer-three-quarters',
		'fas fa-thumbs-down',
		'fas fa-thumbs-up',
		'fas fa-thumbtack',
		'fas fa-ticket-alt',
		'fas fa-times',
		'fas fa-times-circle',
		'fas fa-tint',
		'fas fa-tint-slash',
		'fas fa-tired',
		'fas fa-toggle-off',
		'fas fa-toggle-on',
		'fas fa-toilet-paper',
		'fas fa-toolbox',
		'fas fa-tooth',
		'fas fa-torah',
		'fas fa-torii-gate',
		'fas fa-tractor',
		'fas fa-trademark',
		'fas fa-traffic-light',
		'fas fa-train',
		'fas fa-transgender',
		'fas fa-transgender-alt',
		'fas fa-trash',
		'fas fa-trash-alt',
		'fas fa-tree',
		'fas fa-trophy',
		'fas fa-truck',
		'fas fa-truck-loading',
		'fas fa-truck-monster',
		'fas fa-truck-moving',
		'fas fa-truck-pickup',
		'fas fa-tshirt',
		'fas fa-tty',
		'fas fa-tv',
		'fas fa-umbrella',
		'fas fa-umbrella-beach',
		'fas fa-underline',
		'fas fa-undo',
		'fas fa-undo-alt',
		'fas fa-universal-access',
		'fas fa-university',
		'fas fa-unlink',
		'fas fa-unlock',
		'fas fa-unlock-alt',
		'fas fa-upload',
		'fas fa-user',
		'fas fa-user-alt',
		'fas fa-user-alt-slash',
		'fas fa-user-astronaut',
		'fas fa-user-check',
		'fas fa-user-circle',
		'fas fa-user-clock',
		'fas fa-user-cog',
		'fas fa-user-edit',
		'fas fa-user-friends',
		'fas fa-user-graduate',
		'fas fa-user-injured',
		'fas fa-user-lock',
		'fas fa-user-md',
		'fas fa-user-minus',
		'fas fa-user-ninja',
		'fas fa-user-plus',
		'fas fa-user-secret',
		'fas fa-user-shield',
		'fas fa-user-slash',
		'fas fa-user-tag',
		'fas fa-user-tie',
		'fas fa-user-times',
		'fas fa-users',
		'fas fa-users-cog',
		'fas fa-utensil-spoon',
		'fas fa-utensils',
		'fas fa-vector-square',
		'fas fa-venus',
		'fas fa-venus-double',
		'fas fa-venus-mars',
		'fas fa-vial',
		'fas fa-vials',
		'fas fa-video',
		'fas fa-video-slash',
		'fas fa-vihara',
		'fas fa-volleyball-ball',
		'fas fa-volume-down',
		'fas fa-volume-mute',
		'fas fa-volume-off',
		'fas fa-volume-up',
		'fas fa-vote-yea',
		'fas fa-vr-cardboard',
		'fas fa-walking',
		'fas fa-wallet',
		'fas fa-warehouse',
		'fas fa-water',
		'fas fa-weight',
		'fas fa-weight-hanging',
		'fas fa-wheelchair',
		'fas fa-wifi',
		'fas fa-wind',
		'fas fa-window-close',
		'fas fa-window-maximize',
		'fas fa-window-minimize',
		'fas fa-window-restore',
		'fas fa-wine-bottle',
		'fas fa-wine-glass',
		'fas fa-wine-glass-alt',
		'fas fa-won-sign',
		'fas fa-wrench',
		'fas fa-x-ray',
		'fas fa-yen-sign',
		'fas fa-yin-yang',
		'fab fa-500px',
		'fab fa-accessible-icon',
		'fab fa-accusoft',
		'fab fa-acquisitions-incorporated',
		'fab fa-adn',
		'fab fa-adversal',
		'fab fa-affiliatetheme',
		'fab fa-algolia',
		'fab fa-alipay',
		'fab fa-amazon',
		'fab fa-amazon-pay',
		'fab fa-amilia',
		'fab fa-android',
		'fab fa-angellist',
		'fab fa-angrycreative',
		'fab fa-angular',
		'fab fa-app-store',
		'fab fa-app-store-ios',
		'fab fa-apper',
		'fab fa-apple',
		'fab fa-apple-pay',
		'fab fa-asymmetrik',
		'fab fa-audible',
		'fab fa-autoprefixer',
		'fab fa-avianex',
		'fab fa-aviato',
		'fab fa-aws',
		'fab fa-bandcamp',
		'fab fa-behance',
		'fab fa-behance-square',
		'fab fa-bimobject',
		'fab fa-bitbucket',
		'fab fa-bitcoin',
		'fab fa-bity',
		'fab fa-black-tie',
		'fab fa-blackberry',
		'fab fa-blogger',
		'fab fa-blogger-b',
		'fab fa-bluetooth',
		'fab fa-bluetooth-b',
		'fab fa-btc',
		'fab fa-buromobelexperte',
		'fab fa-buysellads',
		'fab fa-cc-amazon-pay',
		'fab fa-cc-amex',
		'fab fa-cc-apple-pay',
		'fab fa-cc-diners-club',
		'fab fa-cc-discover',
		'fab fa-cc-jcb',
		'fab fa-cc-mastercard',
		'fab fa-cc-paypal',
		'fab fa-cc-stripe',
		'fab fa-cc-visa',
		'fab fa-centercode',
		'fab fa-chrome',
		'fab fa-cloudscale',
		'fab fa-cloudsmith',
		'fab fa-cloudversify',
		'fab fa-codepen',
		'fab fa-codiepie',
		'fab fa-connectdevelop',
		'fab fa-contao',
		'fab fa-cpanel',
		'fab fa-creative-commons',
		'fab fa-creative-commons-by',
		'fab fa-creative-commons-nc',
		'fab fa-creative-commons-nc-eu',
		'fab fa-creative-commons-nc-jp',
		'fab fa-creative-commons-nd',
		'fab fa-creative-commons-pd',
		'fab fa-creative-commons-pd-alt',
		'fab fa-creative-commons-remix',
		'fab fa-creative-commons-sa',
		'fab fa-creative-commons-sampling',
		'fab fa-creative-commons-sampling-plus',
		'fab fa-creative-commons-share',
		'fab fa-creative-commons-zero',
		'fab fa-critical-role',
		'fab fa-css3',
		'fab fa-css3-alt',
		'fab fa-cuttlefish',
		'fab fa-d-and-d',
		'fab fa-d-and-d-beyond',
		'fab fa-dashcube',
		'fab fa-delicious',
		'fab fa-deploydog',
		'fab fa-deskpro',
		'fab fa-dev',
		'fab fa-deviantart',
		'fab fa-digg',
		'fab fa-digital-ocean',
		'fab fa-discord',
		'fab fa-discourse',
		'fab fa-dochub',
		'fab fa-docker',
		'fab fa-draft2digital',
		'fab fa-dribbble',
		'fab fa-dribbble-square',
		'fab fa-dropbox',
		'fab fa-drupal',
		'fab fa-dyalog',
		'fab fa-earlybirds',
		'fab fa-ebay',
		'fab fa-edge',
		'fab fa-elementor',
		'fab fa-ello',
		'fab fa-ember',
		'fab fa-empire',
		'fab fa-envira',
		'fab fa-erlang',
		'fab fa-ethereum',
		'fab fa-etsy',
		'fab fa-expeditedssl',
		'fab fa-facebook',
		'fab fa-facebook-f',
		'fab fa-facebook-messenger',
		'fab fa-facebook-square',
		'fab fa-fantasy-flight-games',
		'fab fa-firefox',
		'fab fa-first-order',
		'fab fa-first-order-alt',
		'fab fa-firstdraft',
		'fab fa-flickr',
		'fab fa-flipboard',
		'fab fa-fly',
		'fab fa-font-awesome',
		'fab fa-font-awesome-alt',
		'fab fa-font-awesome-flag',
		'fab fa-font-awesome-logo-full',
		'fab fa-fonticons',
		'fab fa-fonticons-fi',
		'fab fa-fort-awesome',
		'fab fa-fort-awesome-alt',
		'fab fa-forumbee',
		'fab fa-foursquare',
		'fab fa-free-code-camp',
		'fab fa-freebsd',
		'fab fa-fulcrum',
		'fab fa-galactic-republic',
		'fab fa-galactic-senate',
		'fab fa-get-pocket',
		'fab fa-gg',
		'fab fa-gg-circle',
		'fab fa-git',
		'fab fa-git-square',
		'fab fa-github',
		'fab fa-github-alt',
		'fab fa-github-square',
		'fab fa-gitkraken',
		'fab fa-gitlab',
		'fab fa-gitter',
		'fab fa-glide',
		'fab fa-glide-g',
		'fab fa-gofore',
		'fab fa-goodreads',
		'fab fa-goodreads-g',
		'fab fa-google',
		'fab fa-google-drive',
		'fab fa-google-play',
		'fab fa-google-plus',
		'fab fa-google-plus-g',
		'fab fa-google-plus-square',
		'fab fa-google-wallet',
		'fab fa-gratipay',
		'fab fa-grav',
		'fab fa-gripfire',
		'fab fa-grunt',
		'fab fa-gulp',
		'fab fa-hacker-news',
		'fab fa-hacker-news-square',
		'fab fa-hackerrank',
		'fab fa-hips',
		'fab fa-hire-a-helper',
		'fab fa-hooli',
		'fab fa-hornbill',
		'fab fa-hotjar',
		'fab fa-houzz',
		'fab fa-html5',
		'fab fa-hubspot',
		'fab fa-imdb',
		'fab fa-instagram',
		'fab fa-internet-explorer',
		'fab fa-ioxhost',
		'fab fa-itunes',
		'fab fa-itunes-note',
		'fab fa-java',
		'fab fa-jedi-order',
		'fab fa-jenkins',
		'fab fa-joget',
		'fab fa-joomla',
		'fab fa-js',
		'fab fa-js-square',
		'fab fa-jsfiddle',
		'fab fa-kaggle',
		'fab fa-keybase',
		'fab fa-keycdn',
		'fab fa-kickstarter',
		'fab fa-kickstarter-k',
		'fab fa-korvue',
		'fab fa-laravel',
		'fab fa-lastfm',
		'fab fa-lastfm-square',
		'fab fa-leanpub',
		'fab fa-less',
		'fab fa-line',
		'fab fa-linkedin',
		'fab fa-linkedin-in',
		'fab fa-linode',
		'fab fa-linux',
		'fab fa-lyft',
		'fab fa-magento',
		'fab fa-mailchimp',
		'fab fa-mandalorian',
		'fab fa-markdown',
		'fab fa-mastodon',
		'fab fa-maxcdn',
		'fab fa-medapps',
		'fab fa-medium',
		'fab fa-medium-m',
		'fab fa-medrt',
		'fab fa-meetup',
		'fab fa-megaport',
		'fab fa-microsoft',
		'fab fa-mix',
		'fab fa-mixcloud',
		'fab fa-mizuni',
		'fab fa-modx',
		'fab fa-monero',
		'fab fa-napster',
		'fab fa-neos',
		'fab fa-nimblr',
		'fab fa-nintendo-switch',
		'fab fa-node',
		'fab fa-node-js',
		'fab fa-npm',
		'fab fa-ns8',
		'fab fa-nutritionix',
		'fab fa-odnoklassniki',
		'fab fa-odnoklassniki-square',
		'fab fa-old-republic',
		'fab fa-opencart',
		'fab fa-openid',
		'fab fa-opera',
		'fab fa-optin-monster',
		'fab fa-osi',
		'fab fa-page4',
		'fab fa-pagelines',
		'fab fa-palfed',
		'fab fa-patreon',
		'fab fa-paypal',
		'fab fa-penny-arcade',
		'fab fa-periscope',
		'fab fa-phabricator',
		'fab fa-phoenix-framework',
		'fab fa-phoenix-squadron',
		'fab fa-php',
		'fab fa-pied-piper',
		'fab fa-pied-piper-alt',
		'fab fa-pied-piper-hat',
		'fab fa-pied-piper-pp',
		'fab fa-pinterest',
		'fab fa-pinterest-p',
		'fab fa-pinterest-square',
		'fab fa-playstation',
		'fab fa-product-hunt',
		'fab fa-pushed',
		'fab fa-python',
		'fab fa-qq',
		'fab fa-quinscape',
		'fab fa-quora',
		'fab fa-r-project',
		'fab fa-ravelry',
		'fab fa-react',
		'fab fa-reacteurope',
		'fab fa-readme',
		'fab fa-rebel',
		'fab fa-red-river',
		'fab fa-reddit',
		'fab fa-reddit-alien',
		'fab fa-reddit-square',
		'fab fa-renren',
		'fab fa-replyd',
		'fab fa-researchgate',
		'fab fa-resolving',
		'fab fa-rev',
		'fab fa-rocketchat',
		'fab fa-rockrms',
		'fab fa-safari',
		'fab fa-sass',
		'fab fa-schlix',
		'fab fa-scribd',
		'fab fa-searchengin',
		'fab fa-sellcast',
		'fab fa-sellsy',
		'fab fa-servicestack',
		'fab fa-shirtsinbulk',
		'fab fa-shopware',
		'fab fa-simplybuilt',
		'fab fa-sistrix',
		'fab fa-sith',
		'fab fa-skyatlas',
		'fab fa-skype',
		'fab fa-slack',
		'fab fa-slack-hash',
		'fab fa-slideshare',
		'fab fa-snapchat',
		'fab fa-snapchat-ghost',
		'fab fa-snapchat-square',
		'fab fa-soundcloud',
		'fab fa-speakap',
		'fab fa-spotify',
		'fab fa-squarespace',
		'fab fa-stack-exchange',
		'fab fa-stack-overflow',
		'fab fa-staylinked',
		'fab fa-steam',
		'fab fa-steam-square',
		'fab fa-steam-symbol',
		'fab fa-sticker-mule',
		'fab fa-strava',
		'fab fa-stripe',
		'fab fa-stripe-s',
		'fab fa-studiovinari',
		'fab fa-stumbleupon',
		'fab fa-stumbleupon-circle',
		'fab fa-superpowers',
		'fab fa-supple',
		'fab fa-teamspeak',
		'fab fa-telegram',
		'fab fa-telegram-plane',
		'fab fa-tencent-weibo',
		'fab fa-the-red-yeti',
		'fab fa-themeco',
		'fab fa-themeisle',
		'fab fa-think-peaks',
		'fab fa-trade-federation',
		'fab fa-trello',
		'fab fa-tripadvisor',
		'fab fa-tumblr',
		'fab fa-tumblr-square',
		'fab fa-twitch',
		'fab fa-twitter',
		'fab fa-twitter-square',
		'fab fa-typo3',
		'fab fa-uber',
		'fab fa-uikit',
		'fab fa-uniregistry',
		'fab fa-untappd',
		'fab fa-usb',
		'fab fa-ussunnah',
		'fab fa-vaadin',
		'fab fa-viacoin',
		'fab fa-viadeo',
		'fab fa-viadeo-square',
		'fab fa-viber',
		'fab fa-vimeo',
		'fab fa-vimeo-square',
		'fab fa-vimeo-v',
		'fab fa-vine',
		'fab fa-vk',
		'fab fa-vnv',
		'fab fa-vuejs',
		'fab fa-weebly',
		'fab fa-weibo',
		'fab fa-weixin',
		'fab fa-whatsapp',
		'fab fa-whatsapp-square',
		'fab fa-whmcs',
		'fab fa-wikipedia-w',
		'fab fa-windows',
		'fab fa-wix',
		'fab fa-wizards-of-the-coast',
		'fab fa-wolf-pack-battalion',
		'fab fa-wordpress',
		'fab fa-wordpress-simple',
		'fab fa-wpbeginner',
		'fab fa-wpexplorer',
		'fab fa-wpforms',
		'fab fa-wpressr',
		'fab fa-xbox',
		'fab fa-xing',
		'fab fa-xing-square',
		'fab fa-y-combinator',
		'fab fa-yahoo',
		'fab fa-yandex',
		'fab fa-yandex-international',
		'fab fa-yelp',
		'fab fa-yoast',
		'fab fa-youtube',
		'fab fa-youtube-square',
		'fab fa-zhihu',
	]
} );