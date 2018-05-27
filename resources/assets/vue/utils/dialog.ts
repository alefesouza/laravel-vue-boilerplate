import { create } from 'vue-modal-dialogs';

import BaseDialog from '@/components/BaseDialog.vue';

const dialog = create<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

export default dialog;
