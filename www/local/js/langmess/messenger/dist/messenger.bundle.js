/* eslint-disable */
this.BX = this.BX || {};
this.BX.Messenger = this.BX.Messenger || {};
(function (exports, im_application_core, im_controller, im_provider_rest, main_core_events, ui_vue, ui_vue_vuex, im_lib_utils, im_const, im_component_recent, im_component_dialog, im_component_textarea, pull_component_status, ui_entitySelector, im_eventHandler) {
	'use strict';

	/**
	 * Bitrix Im
	 * Application Messenger view
	 *
	 * @package bitrix
	 * @subpackage im
	 * @copyright 2001-2020 Bitrix
	 */

	ui_vue.BitrixVue.component('bx-im-application-messenger', {
		props: {
			userId: {
				type: Number,
				default: 0
			},
		},
		data() {
			return {
				selectedDialogId: this.dialogId,
				notificationsSelected: false,
				textareaHeight: 120
			};
		},
		computed: {
			DeviceType: () => im_const.DeviceType,
			textareaHeightStyle() {
				return {
					flex: `0 0 ${this.textareaHeight}px`
				};
			},
			isDialog() {
				return im_lib_utils.Utils.dialog.isChatId(this.selectedDialogId);
			},
			chatId() {
				if (this.application) {
					return this.application.dialog.chatId;
				}
				return 0;
			},
			dialogId() {
				if (this.application) {
					return this.application.dialog.dialogId;
				}
				return 0;
			},
			localize() {
				return ui_vue.BitrixVue.getFilteredPhrases(['IM_DIALOG_', 'IM_UTILS_', 'IM_MESSENGER_DIALOG_', 'IM_QUOTE_'], this);
			},
			...ui_vue_vuex.Vuex.mapState({
				application: state => state.application
			})
		},
		created() {
			this.initEventHandlers();
			this.searchPopup = null;
			this.subscribeToEvents();
			this.openMessenger(this.dialogId)
		},
		beforeDestroy() {
			this.unsubscribeEvents();
			this.destroyHandlers();
		},
		methods: {
			// region handlers
			initEventHandlers() {
				this.textareaDragHandler = this.getTextareaDragHandler();
				this.readingHandler = new im_eventHandler.ReadingHandler(this.$Bitrix);
				this.reactionHandler = new im_eventHandler.ReactionHandler(this.$Bitrix);
				this.quoteHandler = new im_eventHandler.QuoteHandler(this.$Bitrix);
				this.textareaHandler = new im_eventHandler.TextareaHandler(this.$Bitrix);
				this.sendMessageHandler = new im_eventHandler.SendMessageHandler(this.$Bitrix);
				this.textareaUploadHandler = new im_eventHandler.TextareaUploadHandler(this.$Bitrix);
				this.dialogActionHandler = new im_eventHandler.DialogActionHandler(this.$Bitrix);
			},
			destroyHandlers() {
				this.textareaDragHandler.destroy();
				this.readingHandler.destroy();
				this.reactionHandler.destroy();
				this.quoteHandler.destroy();
				this.textareaHandler.destroy();
				this.textareaUploadHandler.destroy();
				this.dialogActionHandler.destroy();
			},
			getTextareaDragHandler() {
				return new im_eventHandler.TextareaDragHandler({
					[im_eventHandler.TextareaDragHandler.events.onHeightChange]: ({
						data
					}) => {
						const {
							newHeight
						} = data;
						if (this.textareaHeight !== newHeight) {
							this.textareaHeight = newHeight;
						}
					},
					[im_eventHandler.TextareaDragHandler.events.onStopDrag]: () => {
						main_core_events.EventEmitter.emit(im_const.EventType.dialog.scrollToBottom, {
							chatId: this.chatId,
							force: true
						});
					}
				});
			},
			// endregion handlers
			openMessenger(dialogId) {
				dialogId = dialogId.toString();
				if (dialogId === 'notify') {
					this.selectedDialogId = 0;
					this.notificationsSelected = true;
				} else {
					this.selectedDialogId = dialogId;
					this.notificationsSelected = false;
				}
			},
			// region events
			subscribeToEvents() {
				// main_core_events.EventEmitter.subscribe(im_const.EventType.dialog.open, this.onOpenMessenger);
			},
			unsubscribeEvents() {
				// main_core_events.EventEmitter.unsubscribe(im_const.EventType.dialog.open, this.onOpenMessenger);
			},
			onOpenMessenger({data}) {
				this.openMessenger(data.id);
			},
			onTextareaStartDrag(event) {
				this.textareaDragHandler.onStartDrag(event, this.textareaHeight);
				main_core_events.EventEmitter.emit(im_const.EventType.textarea.setBlur, true);
			}
			// endregion events
		},
		// language=Vue
		template: `
			<div class="w-100 h-100 d-f fd-c">
				<div class="w-100 h-100 d-f fd-c" v-if="selectedDialogId">
					<div class="w-100 pix-24 pby-16 d-f bbw-1 bbs-s bbc-E5E5E5">
						<div class="f-14">Dialog: {{selectedDialogId}}</div>
					</div>
					<div class="bx-im-next-layout-dialog-messages">
							<bx-pull-component-status/>
						<bx-im-component-dialog
							:userId="userId" 
							:dialogId="selectedDialogId"
							:showMessageUserName="isDialog"
							:showMessageAvatar="isDialog"
						/>
					</div>
					<div class="bx-im-next-layout-dialog-textarea" :style="textareaHeightStyle" ref="textarea">
							<div class="bx-im-next-layout-dialog-textarea-handle" @mousedown="onTextareaStartDrag" @touchstart="onTextareaStartDrag"></div>
						<bx-im-component-textarea
							:siteId="application.common.siteId"
							:userId="userId"
							:dialogId="selectedDialogId"
							:writesEventLetter="3"
							:enableEdit="true"
							:enableCommand="false"
							:enableMention="false"
							:enableFile="false"
							:autoFocus="application.device.type !== DeviceType.mobile"
						/>
					</div>
				</div>
				<div class="bx-im-next-layout-notify" v-else-if="notificationsSelected">
					<bx-im-component-notifications :darkTheme="false"/>
				</div>
				<div class="bx-im-next-layout-notify" v-else>
					<div class="bx-messenger-box-hello-wrap">
						<div class="bx-messenger-box-hello">{{ $Bitrix.Loc.getMessage('IM_M_EMPTY') }}</div>
					</div>
				</div>
			</div>
		`
	});

	/**
	 * Bitrix Im
	 * Messenger application
	 *
	 * @package bitrix
	 * @subpackage im
	 * @copyright 2001-2020 Bitrix
	 */
	class MessengerApplication {
		inited = false;
		initPromise = null;
		initPromiseResolver = null;
		vueInstance = null;
		controller = null;
		rootNode = null;

		/* region 01. Initialize */
		constructor(params = {}) {
			this.initPromise = new Promise(resolve => {
				this.initPromiseResolver = resolve;
			});
			this.params = params;
			this.rootNode = this.params.node || document.createElement('div');
			this.initCore().then(() => this.initComponent()).then(() => this.initComplete());
		}
		initCore() {
			return new Promise(resolve => {
				im_application_core.Core.ready().then(controller => {
					this.controller = controller;
					resolve();
				});
			});
		}
		initComponent() {
			this.setInitialApplicationInfo();
			this.setDialogRestHandler();
			this.setApplicationDialogInfo();
			return this.controller.createVue(this, {
				el: this.rootNode,
				data: () => {
					return {
						userId: this.getUserId(),
						dialogId: this.getDialogId(),
					};
				},
				// language=Vue
				template: `<bx-im-application-messenger :userId="userId" :dialogId="dialogId" />`
			}).then(vue => {
				this.vueInstance = vue;
				return Promise.resolve();
			});
		}
		initComplete() {
			this.inited = true;
			this.initPromiseResolver(this);
		}
		ready() {
			if (this.inited) {
				return Promise.resolve(this);
			}
			return this.initPromise;
		}

		/* endregion 01. Initialize */

		/* region 02. Methods */
		setInitialApplicationInfo() {
			this.controller.getStore().commit('application/set', {
				dialog: {
					dialogId: this.getDialogId()
				},
				options: {
					quoteEnable: true,
					autoplayVideo: true,
					darkBackground: false
				}
			});
		}
		setApplicationDialogInfo() {
			const dialog = this.controller.getStore().getters['dialogues/get'](this.getDialogId());
			if (!dialog) {
				return false;
			}
			this.controller.getStore().commit('application/set', {
				dialog: {
					chatId: dialog.chatId,
					diskFolderId: dialog.diskFolderId || 0
				}
			});
		}
		setDialogRestHandler() {
			this.controller.addRestAnswerHandler(im_provider_rest.DialogRestHandler.create({
				store: this.controller.getStore(),
				controller: this.controller,
				context: this
			}));
		}
		getUserId() {
			const userId = this.params.userId || this.getLocalize('USER_ID');
			return userId ? Number.parseInt(userId, 10) : 0;
		}
		getDialogId() {
			return this.params.dialogId ? this.params.dialogId.toString() : "0";
		}
		getHost() {
			return location.origin || '';
		}
		getSiteId() {
			return 's1';
		}
		addLocalize(phrases) {
			return this.controller.addLocalize(phrases);
		}
		getLocalize(name) {
			return this.controller.getLocalize(name);
		}
		/* endregion 02. Methods */
	}

	exports.MessengerApplication = MessengerApplication;

})(this.BX.Messenger.Application = this.BX.Messenger.Application || {}, BX.Messenger.Application, BX.Messenger, BX.Messenger.Provider.Rest, BX.Event, BX, BX, BX.Messenger.Lib, BX.Messenger.Const, BX.Messenger, BX.Messenger, window, window, BX.UI.EntitySelector, BX.Messenger.EventHandler);
//# sourceMappingURL=messenger.bundle.js.map
