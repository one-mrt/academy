/* eslint-disable */
this.BX = this.BX || {};
this.BX.Messenger = this.BX.Messenger || {};
this.BX.Messenger.Embedding = this.BX.Messenger.Embedding || {};
(function (
	exports,
	im_oldChatEmbedding_const,
	im_oldChatEmbedding_lib_utils,
	im_oldChatEmbedding_application_core
) {
	'use strict';

	const AvatarSize = {
		XS: 'XS',
		S: 'S',
		M: 'M',
		L: 'L',
		XL: 'XL',
		XXL: 'XXL'
	};
	const Avatar = {
		name: 'Avatar',
		props: {
			dialogId: {
				type: String,
				default: '0'
			},
			size: {
				type: String,
				default: AvatarSize.M
			},
			withAvatarLetters: {
				type: Boolean,
				default: true
			},
			withStatus: {
				type: Boolean,
				default: true
			},
			withCounter: {
				type: Boolean,
				default: false
			},
			withBirthday: {
				type: Boolean,
				default: false
			},
			withTyping: {
				type: Boolean,
				default: false
			}
		},
		computed: {
			dialog() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let dialog = store.getters['dialogues/get'](this.dialogId, true)
				if(dialog) {
					return dialog
				}

				return {}
			},
			user() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let user = store.getters['users/get'](this.dialogId, true);

				if(user) {
					return user
				}
				
				return {}
			},
			isUser() {
				if(Number(this?.user?.id) > 1) {
					
					if(!this.dialog) {
						this.dialog = {}
					}

					if(this?.user?.avatar) {
						this.dialog.avatar = ""
						this.dialog.avatar = this.user.avatar
					}
					if(this?.user?.name) {
						this.dialog.name = ""
						this.dialog.name = this.user.name
					}
					if(this?.user?.type) {
						this.dialog.type = ""
						this.dialog.type = this.user.type
					}
					if(this?.user?.color) {
						this.dialog.color = ""
						this.dialog.color = this.user.color
					}
					
					return true;
				}

				return false
			},
			isBot() {
				if (this.isUser) {
					return this.user.bot;
				}
				return false;
			},
			isActiveInvitation() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				const recentItem = store.getters['recent/get'](this.dialogId);
				if (!recentItem) {
					return false;
				}
				return recentItem?.invitation?.isActive;
			},
			chatAvatarStyle() {
				return {
					backgroundImage: `url('${this.dialog?.avatar}')`
				};
			},
			avatarText() {
				if (![im_oldChatEmbedding_const.DialogType.user, im_oldChatEmbedding_const.DialogType.open, im_oldChatEmbedding_const.DialogType.chat].includes(this?.dialog?.type)) {
					return '';
				}
				return im_oldChatEmbedding_lib_utils.Utils.text.getFirstLetters(this?.dialog?.name);
			},
			chatTypeIconClasses() {
				const classes = [];
				if (im_oldChatEmbedding_const.DialogType[this?.dialog?.type]) {
					classes.push(`bx-im-component-avatar-icon-${this?.dialog?.type}`);
				} else {
					classes.push('bx-im-component-avatar-icon-default');
				}
				return classes;
			},
			userStatusIcon() {
				if (!this.isUser || this.isBot || this.user.id === this.currentUserId) {
					return '';
				}
				
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let user = store.getters['users/get'](this.dialogId)

				if(user) {
					return user.status
				}
				
				return '';
			},
			isSomeoneTyping() {
				return Object.keys(this.dialog.writingList).length > 0;
			},
			formattedCounter() {
				return this.dialog.counter > 99 ? '99+' : this.dialog.counter;
			},
			isChatMuted() {
				if (this.isUser) {
					return false;
				}
				const isMuted = this.dialog.muteList.find(element => {
					return element === this.currentUserId;
				});
				return !!isMuted;
			},
			currentUserId() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				return store.state.application.common.userId;
			}
		},
		template: `
		<div :title="dialog?.name" :class="'bx-im-component-avatar-size-' + size.toLowerCase()" class="bx-im-recent-item-image-wrap">
			<div v-if="isActiveInvitation" class="bx-im-component-avatar-content bx-im-component-avatar-invitation"></div>
			<div v-else-if="dialog?.avatar" :style="chatAvatarStyle" class="bx-im-component-avatar-content bx-im-component-avatar-image"></div>
			<div v-else-if="withAvatarLetters && avatarText" :style="{backgroundColor: dialog?.color}" class="bx-im-component-avatar-content bx-im-component-avatar-text">
				{{ avatarText }}
			</div>
			<div v-else :style="{backgroundColor: dialog?.color}" :class="chatTypeIconClasses" class="bx-im-component-avatar-content bx-im-component-avatar-icon"></div>
			<div v-if="withTyping && isSomeoneTyping" class="bx-im-component-avatar-user-status-icon bx-im-component-avatar-user-status-icon-typing"></div>
			<div v-else-if="withStatus && userStatusIcon" :class="'bx-im-recent-icon-avatar-bottom-right bx-im-recent-avatar-bottom-right-' + userStatusIcon"></div>
			<div v-if="withCounter && dialog?.counter > 0" :class="{'bx-im-component-avatar-counter-muted': isChatMuted}" class="bx-im-component-avatar-counter">
				{{ formattedCounter }}
			</div>
		</div>
	`
	};
	const ChatTitle = {
		name: 'ChatTitle',
		props: {
			dialogId: {
				type: String,
				default: '0'
			},
			withMute: {
				type: Boolean,
				default: false
			}
		},
		computed: {
			dialog() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let dialog = store.getters['dialogues/get'](this.dialogId, true)
				if(dialog) {
					return dialog
				}

				return {}
			},
			user() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				let user = store.getters['users/get'](this.dialogId, true);

				if(user) {
					return user
				}
				
				return {}
			},
			botType() {
				if (!this.isUser) {
					return '';
				}
				let store = im_oldChatEmbedding_application_core.Core.getStore();

				return store.getters['users/getBotType'](this.dialogId);
			},
			isUser() {
				
				if(Number(this?.user?.id) > 1) {
					if(!this.dialog) {
						this.dialog = {}
					}
					if(this?.user?.avatar) {
						this.dialog.avatar = ""
						this.dialog.avatar = this.user.avatar
					}
					if(this?.user?.name) {
						this.dialog.name = ""
						this.dialog.name = this.user.name
					}
					if(this?.user?.type) {
						this.dialog.type = ""
						this.dialog.type = this.user.type
					}
					if(this?.user?.color) {
						this.dialog.color = ""
						this.dialog.color = this.user.color
					}
					
					return true;
				}
				return false
				// return this?.dialog?.type === im_oldChatEmbedding_const.DialogType.user;
			},
			isSelfChat() {
				return this.isUser && this.user.id === this.currentUserId;
			},
			// dialogSpecialType() {
			// 	if (!this.isUser) {
			// 		if (this.isExtranet) {
			// 			return DialogSpecialType.extranet;
			// 		} else if ([im_oldChatEmbedding_const.DialogType.support24Notifier, im_oldChatEmbedding_const.DialogType.support24Question].includes(this?.dialog?.type)) {
			// 			return DialogSpecialType.support24;
			// 		}
			// 		return '';
			// 	}
			// 	if (this.isBot) {
			// 		return this.botType;
			// 	}
			// 	/* else if (this.isExtranet) {
			// 		return DialogSpecialType.extranet;
			// 	} else if (this.isNetwork) {
			// 		return DialogSpecialType.network;
			// 	}*/
			// 	return '';
			// },
			leftIcon() {
				// if (this.dialogSpecialType) {
				// 	return this.dialogSpecialType;
				// }
				if (!this.isUser) {
					return '';
				}
				// if (this.showBirthdays && this.user.isBirthday) {
				// 	return TitleIcons.birthday;
				// } else if (this.user.isAbsent) {
				// 	return TitleIcons.absent;
				// }
				return '';
			},
			color() {
				return this.dialogSpecialType;
			},
			isBot() {
				if (this.isUser) {
					return this.user.bot;
				}
				return false;
			},
			isExtranet() {
				if (this.isUser) {
					return this.user.extranet;
				}
				return this?.dialog?.extranet;
			},
			isNetwork() {
				if (this.isUser) {
					return this.user.network;
				}
				return false;
			},
			isChatMuted() {
				if (this.isUser) {
					return false;
				}
				const isMuted = this.dialog.muteList.find(element => {
					return element === this.currentUserId;
				});
				return !!isMuted;
			},
			currentUserId() {
				let store = im_oldChatEmbedding_application_core.Core.getStore();
				return store.state.application.common.userId;
			},
			tooltipText() {
				if (this.isSelfChat && this?.dialog?.name) {
					return `${this?.dialog?.name} (${this.loc('IM_RECENT_CHAT_SELF')})`;
				}
				return this?.dialog?.name ?? "Диалог";
			},
			// showBirthdays() {
			// 	console.info(this.$store.getters)
			// 	return this.$store.getters['recent/getOption'](im_oldChatEmbedding_const.RecentSettings.showBirthday);
			// },
			// isDarkTheme() {
			// 	let store = im_oldChatEmbedding_application_core.Core.getStore();
			// 	return store.state.application.options.darkTheme;
			// }
		},
		methods: {
			loc(phraseCode) {
				return this.$Bitrix.Loc.getMessage(phraseCode);
			}
		},
		template: `
		<div class="bx-im-recent-item-content-header">
			<div v-if="leftIcon" :class="'bx-im-component-chat-name-left-icon bx-im-component-chat-name-left-icon-' + leftIcon"></div>
			<span :class="'bx-im-component-chat-name-text-' + color" :title="tooltipText" class="f-14">
				{{ dialog?.name }}
				<strong v-if="isSelfChat">
					<span class="bx-im-component-chat-name-text-self">({{ loc('IM_RECENT_CHAT_SELF') }})</span>
				</strong>
			</span>
			<div v-if="withMute && isChatMuted" class="bx-im-component-chat-name-muted-icon"></div>
		</div>
	`
	};

	const RecentLoadingState = {
		name: 'RecentLoadingState',
		props: {
			compactMode: {
				type: Boolean,
				default: false
			},
			itemsToShow: {
				type: Number,
				default: 50
			}
		},
		methods: {
			isThreeLineVersion() {
				return Math.random() < 0.5;
			}
		},
		template: `
		<div v-if="!compactMode" class="bx-im-component-recent-loading-state">
			<div v-for="index in itemsToShow" class="bx-im-component-recent-loading-state-item">
				<div class="bx-im-component-recent-loading-state-avatar-wrap">
					<div class="bx-im-component-recent-loading-state-avatar-placeholder"></div>
				</div>
				<div class="bx-im-component-recent-loading-state-content">
					<div class="bx-im-component-recent-loading-state-line bx-im-component-recent-loading-state-line-long"></div>
					<div class="bx-im-component-recent-loading-state-line bx-im-component-recent-loading-state-line-short"></div>
					<div v-if="isThreeLineVersion()" class="bx-im-component-recent-loading-state-line bx-im-component-recent-loading-state-line-short"></div>
				</div>
			</div>
		</div>
		<div v-if="compactMode" class="bx-im-component-recent-loading-state bx-im-component-recent-loading-state-compact">
			<div v-for="index in itemsToShow" class="bx-im-component-recent-loading-state-item">
				<div class="bx-im-component-recent-loading-state-avatar-wrap">
					<div class="bx-im-component-recent-loading-state-avatar-placeholder"></div>
				</div>
			</div>
		</div>
	`
	};

	exports.Avatar = Avatar;
	exports.AvatarSize = AvatarSize;
	exports.ChatTitle = ChatTitle;
	exports.RecentLoadingState = RecentLoadingState;

})(
	this.BX.Messenger.Embedding.ComponentLegacy = this.BX.Messenger.Embedding.ComponentLegacy || {},
	BX.Messenger.Embedding.Const,
	BX.Messenger.Embedding.Lib,
	BX.Messenger.Embedding.Application
);
