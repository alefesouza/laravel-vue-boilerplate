import { makeDialog } from 'vue-modal-dialogs';

import BaseDialog from '@/components/BaseDialog.vue';

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

export default dialog;
