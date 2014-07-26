CREATE TABLE wp_wpd_catalog
			(
			col_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
			,col_name                       varchar(200)
			,data_type                      varchar(64)
			,wpd_extend_type                varchar(64)
			,input_type                     varchar(64)
			,edit_methoed                   varchar(64)
			,input_support                  varchar(1024)
			,validation                     varchar(1024)
			,individual_page_nonpublic      varchar(3)
			,list_page_show                 varchar(3)
			,table_page_show                varchar(3)
			,admin_list_show                varchar(3)
			,item_name                      text
			,item_info                      text
			,UNIQUE KEY wp_wpd_catalog_col_id (col_id)
			)
			CHARACTER SET 'utf8';";
			

insert into wp_wpd_catalog values(
"1",
"meta_id",
"bigint(20)",
"",
"hidden",
"����",
"",
"",
"",
"",
"",
"",
"�f�[�^ID",
"�o�^������ӂɎ��ʂ���ID�ł��B<BR>�wWANS:�����x�̌`���ŕ\�������ꍇ������܂��B"
);


insert into wp_wpd_catalog values(
"2",
"post_id",
"bigint(20)",
"",
"hidden",
"����",
"",
"",
"",
"",
"",
"",
"Wordpress��POST ID",
"Wordpress��POST ID<BR>�{���͔�\���ł��B<BR>Wordpress�̊Ǘ�ID�ƂȂ�܂��B"
);

insert into wp_wpd_catalog values(
"3",
"pet_name",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_unique_check",
"input_mandatory",
"",
"",
"",
"",
"�y�b�g�̖��O",
"�y�b�g�̖��O�ł��B<BR>�J�[�\�����O���Əd�������l���Ȃ��������I�Ƀ`�F�b�N���܂��B<BR>�d�������l������ƃ��b�Z�[�W���o�͂���A�w���J�x�{�^�������������܂��B<BR>�������O�����ɓo�^����Ă���ꍇ�́A�Ȃɂ��j�b�N�l�[����n�於��ǉ����A�d�����Ă��Ȃ����O�ɕύX���ĉ������B"
);

insert into wp_wpd_catalog values(
"4",
"birthyear_almost_flag",
"VARCHAR(10)",
"",
"checkbox",
"input",
"",
"",
"",
"",
"",
"",
"���m�Ȓa�������s���̏ꍇ�Ƀ`�F�b�N����",
"���m�Ȓa�������s���̏ꍇ�Ƀ`�F�b�N���܂��B<BR>�`�F�b�N������ƁA�N��Ɂw���������x�Ƃ����������ǉ�����܂��B"
);


insert into wp_wpd_catalog values(
"5",
"birthyear",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"�a����(���t�`���̂�)",
"�a��������͂��܂��B<BR>�s���̏ꍇ�͂����悻�̓��t����͂��āA�w���������x�Ƀ`�F�b�N�����ĉ������B<BR>�����͂̏ꍇ�� �f�[�^��o�^������A0000-00-00 �Ƃ����l�������I�ɑ}������܂��B"
);


insert into wp_wpd_catalog values(
"6",
"Deathyear",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"�v�N(���t�`���̂�)",
"�v�N����͂��܂��B<BR>�����͂̏ꍇ�� �f�[�^��o�^������A0000-00-00 �Ƃ����l�������I�ɑ}������܂��B<BR>���̏ꍇ�A���J�y�[�W�ɂ͂���̏�Ԃŕ\������܂��B"
);

insert into wp_wpd_catalog values(
"7",
"photo",
"text",
"",
"hidden",
"�摜�A�b�v���[�h",
"media-upload",
"",
"",
"",
"",
"",
"�v���t�B�[���ʐ^(�A�b�v���[�h or �I��)",
"�v���t�B�[���ʐ^���A�b�v���[�h���܂��B<BR>�܂��A���f�B�A���C�u��������A�b�v���[�h�ς݂̉摜��I�����邱�Ƃ��o���܂��B<BR>�A�b�v���[�h��́A�I���W�i���摜�ɕ\�������̂ŁA�v���r���[�̍��ڂ����Ȃ���\�������͈͂�؂蔲���Ŏw�肵�ĉ������B<BR>���A���ۂ̉摜�́A���f�B�A���C�u�����ł͂Ȃ� ��p�̃f�B���N�g���ɁAmeta_id.jpg �̌`���ŕۑ�����Ă��܂��B"
);

insert into wp_wpd_catalog values(
"8",
"photo_coordinates",
"text",
"",
"hidden",
"Jcrop",
"Jcrop",
"",
"",
"",
"",
"",
"�v���t�B�[���ʐ^�v���r���[(�؂�����W�ݒ�)",
"�ʐ^�̃T�C�Y�����؂�����W�ł��B<BR>�I���W�i���摜��؂蔲���ۂɎ����I�ɎZ�o����Ă��܂��B"
);

insert into wp_wpd_catalog values(
"9",
"sex",
"VARCHAR(10)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"",
"",
"",
"",
"����",
"���ʂł��B<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B<BR>��{�I�ɂ̓I�X/���X�݂̂ł��� �s�� �ȂǁA���R�ɋL���o���܂��B"
);

insert into wp_wpd_catalog values(
"10",
"color",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"�F(�X���b�V��(/)��؂�Ŏ�������)",
"�F�ł��B<BR>�����̐F���X���b�V��(/)�ŋ�؂��Ďw�肷�邱�Ƃ��o���܂��B<BR>(��:��/���F)<BR>���A�����̒l������ꍇ�A�J�[�\�����O���Ǝ����I�ɐ�������܂��B<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"11",
"breed",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"����/���(�X���b�V��(/)��؂�Ŏ�������)",
"������ނł�<BR>�����̐F���X���b�V��(/)�ŋ�؂��Ďw�肷�邱�Ƃ��o���܂��B<BR>(��:MIX/�_�b�N�X/�v�[�h��)<BR>���A�����̒l������ꍇ�A�J�[�\�����O���Ǝ����I�ɐ�������܂�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"12",
"breeds_size",
"VARCHAR(30)",
"",
"select",
"input",
"",
"",
"",
"",
"",
"",
"���������̑傫��",
"�傫���ł�<BR>0.�s��/1.���^/2.���^/3.��^ ��4��ނ���I�ׂ܂�<BR>�����悻�ō\���܂���<BR>�ǉ������ꍇ�͊Ǘ��҂ɂ����k���������B"
);

insert into wp_wpd_catalog values(
"13",
"wans_reg_date",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"�����Y�ɓo�^���ꂽ��(���t�`���̂�)",
"�����Y�ɓo�^���ꂽ���ł�<BR>�s���̏ꍇ�́A�����悻�̓��t����͂��ĉ�����<BR>�����͂̏ꍇ�� �f�[�^��o�^������A0000-00-00 �Ƃ����l�������I�ɑ}������܂��B"
);

insert into wp_wpd_catalog values(
"14",
"now_status",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"input_mandatory",
"",
"",
"",
"",
"���݂̃X�e�[�^�X(�X���b�V��(/)��؂�Ŏ�������)",
"���݂̃X�e�[�^�X����͂��܂�<BR>�w���e��W�x�w���n�ς݁x�w��񂸂؁`���ցx���̕������L�����܂�<BR>��{�I�ɂ͂ǂ̂悤�ȕ����ł��\���܂���<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ�����<BR>"
);

insert into wp_wpd_catalog values(
"15",
"recent_status_change",
"DATE",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"���߂̃X�e�[�^�X�ύX��(���t�`���̂�)",
"���݂̃X�e�[�^�X�ɕύX���ꂽ���t����͂��ĉ�����<BR>�ʏ�́A�ŏ��̒i�K�ł͓o�^���ꂽ���t�ƈ�v����Ǝv���܂�<BR>���n���ꂽ���񂸂؁`���֗��������ꍇ�́A���̓��t����͂��܂�<BR>�s���̏ꍇ�́A�����悻�̓��t����͂��ĉ�����<BR>�����͂̏ꍇ�� �f�[�^��o�^������A0000-00-00 �Ƃ����l�������I�ɑ}������܂��B"
);

insert into wp_wpd_catalog values(
"16",
"status_history",
"text",
"YES",
"text",
"input",
"",
"",
"",
"",
"",
"",
"�X�e�[�^�X����",
"����܂ł̃X�e�[�^�X�Ƃ��̃X�e�[�^�X�ɕύX���ꂽ���t����͂��܂�<BR>���͌�J�[�\�����O���Ǝ����I�Ɏ��ԏ��ɐ�������܂�<BR>"
);

insert into wp_wpd_catalog values(
"17",
"neutering",
"VARCHAR(10)",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"��D������(���t or �����B�����{�͋�)",
"������D���s�������t����͂��ĉ�����<BR>�����{�̏ꍇ�ɂ́A�K����̂܂܂ɂ��ĉ�����<BR>��{�I�ɂ͓��t�`���œ��͂��܂����A�w2014/06/14�\��x���t���[�t�H�[�}�b�g���\�ł�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^(���t�ȊO)���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"18",
"vaccine",
"VARCHAR(10)",
"",
"text",
"input",
"datepicker",
"",
"",
"",
"",
"",
"���N�`���ڎ�(���t or �����B�����{�͋�)",
"���N�`���ڎ��ڎ킵�����t��A���{�󋵂���͂��ĉ�����<BR>���ێ�̏ꍇ�ɂ́A�K����̂܂܂ɂ��ĉ�����<BR>�w�ڎ�ς݁x�Ȃǁw3�퍬���̂݁x���t���[�t�H�[�}�b�g���\�ł�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^(���t�ȊO)���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"19",
"health_condition",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"",
"",
"",
"",
"",
"���N���(�X���b�V��(/)��؂�Ŏ�������)",
"���N��Ԃ���͂��ĉ�����<BR>�����̏�ԓ��͂��鎞�́A�X���b�V��(/)�ŋ�؂��Ďw�肷�邱�Ƃ��o���܂��B(��:�t�B�����A��/���O���ɍ��ܗ�����<BR>���A�����̒l������ꍇ�A�J�[�\�����O���Ǝ����I�ɐ�������܂�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"20",
"why_is_here",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete autocomplete_multiple",
"input_mandatory",
"",
"",
"",
"",
"�����Y�ɓo�^���ꂽ���R(�ی�A�����O��)",
"�o�܂Ɋւ���v�����͂��ĉ�����<BR>�����̏�ԓ��͂��鎞�́A�X���b�V��(/)�ŋ�؂��Ďw�肷�邱�Ƃ��o���܂��B(��:�ی�/����s��������<BR>���A�����̒l������ꍇ�A�J�[�\�����O���Ǝ����I�ɐ�������܂�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);

insert into wp_wpd_catalog values(
"21",
"story",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"���i/�X�g�[���[",
"���i��o�܂Ɋւ�������t���[�t�H�[�}�b�g�œ��͂��܂�<BR>���������͂قƂ�ǂ���܂���<BR>");

insert into wp_wpd_catalog values(
"22",
"supplement",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"���n�ɍۂ���⑫����",
"���e�Ƃ��Č}�������A�g���C�A������]���Ă���l�ւ́A�ǉ��̃��b�Z�[�W�Ȃǂ��t���[�t�H�[�}�b�g�œ��͂��܂�<BR>(��:���Ô�����鎖���\�z����܂�<BR>���������͂قƂ�ǂ���܂���B");

insert into wp_wpd_catalog values(
"23",
"additional_condition",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"�ǉ��̏��n����",
"���m�Ȓǉ��̏��n����������ꍇ�͂�����Ƀt���[�t�H�[�}�b�g�œ��͂��܂�<BR>(��:��������s��/�������s��<BR>���������͂قƂ�ǂ���܂���B");

insert into wp_wpd_catalog values(
"24",
"additional_cost",
"text",
"",
"textarea",
"input",
"",
"",
"",
"",
"",
"",
"���n�̍ۂ̒ǉ���p",
"���n�̍ۂ̒ǉ���p���t���[�t�H�[�}�b�g�œ��͂��܂�<BR>(��:����������Ô�ܔ��Ƃ��āA�ǉ���50000�~�����܂��B<BR>���������͂قƂ�ǂ���܂���B");

insert into wp_wpd_catalog values(
"25",
"note",
"text",
"",
"textarea",
"input",
"",
"",
"����J",
"",
"",
"",
"����J�̃���(���ł�/�t���[�t�H�[�}�b�g)",
"�Ǘ��p�̃����ł��B��ʂɂ͌��J����܂���<BR>���J�ł��Ȃ��o�܂���L�������L�ڂ��܂�<BR>���������͂قƂ�ǂ���܂���B");

insert into wp_wpd_catalog values(
"26",
"facebookurl",
"text",
"",
"text",
"input",
"",
"",
"",
"",
"",
"",
"Facebook�y�[�W��URL",
"Facebook�y�[�W�w���e��W���I����'s�p�[�g�i�[�̉�x�Ōf�ڂ��Ă���ꍇ�́A����URL����͂��܂��B");

insert into wp_wpd_catalog values(
"27",
"photo_url",
"text",
"",
"text",
"input",
"",
"",
"",
"",
"",
"",
"WEB���L�h���C�u��URL(�摜�������擾)",
"�����̎ʐ^���f�ڂ������ꍇ�́Agoogle�h���C�u���Ɏʐ^��z�u���܂�<BR>�v���t�@�C��2 - Google �h���C�u<BR>https://drive.google.com/?usp=folder&authuser=0#folders/0BzpLZwemcKepRlpHX0dHRmdaMT<BR>�����̍��ڂł́AGoogle�h���C�u���w���L�x�̑���ŕ\������� URL ����͂��܂�<BR>URL��̏��������I�Ɏ擾���āA���J�y�[�W�ɕ\�������Ă��܂�<BR>�Ή�����t�H���_���Ȃ��ꍇ��摜���A�b�v���[�h�������ꍇ�́A�Ǘ��҂ɂ����k�������B"
);

insert into wp_wpd_catalog values(
"28",
"phote_fb_url",
"text",
"",
"text",
"input",
"",
"",
"����J",
"",
"",
"",
"Facebook�ŊǗ����Ă���ʐ^��URL(URL�̂ݕ\��)",
"�����Ƃ���Facebook��̎ʐ^��URL����͂��܂�<BR>���A�摜�̎����擾�͍s���܂���B");

insert into wp_wpd_catalog values(
"29",
"detail_paper",
"VARCHAR(30)",
"",
"text",
"input",
"",
"",
"����J",
"",
"",
"",
"�`���V�͍쐬�ς݂��ǂ���",
"���n���ɔz�u����`���V���쐬�ς݂��ǂ�������͂��܂�<BR>���쐬�̏ꍇ�ɂ́A�K����̂܂܂ɂ��ĉ�����<BR>�쐬�ς݂̏ꍇ�́w�쐬�ς݁x�Ȃǃt���[�t�H�[�}�b�g�œ��͂��܂��B"
);

insert into wp_wpd_catalog values(
"30",
"related_url",
"text",
"YES",
"text",
"input",
"",
"",
"",
"",
"",
"",
"�֘A�����N(URL�ƃ^�C�g�������)",
"�֘A����URL������͂��܂�<BR>������URL���A�E���ɕ\�������L�ڂ��܂�<BR>BLOG�L���⑼�T�C�g��URL�ł���肠��܂���<BR>���ڂ�����Ȃ��ꍇ�́w+�x�{�^�����N���b�N���ĉ������B"
);


insert into wp_wpd_catalog values(
"31",
"depository",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"����J",
"",
"",
"",
"�ی�厁��",
"�ی��̎������A�h�̗��œ��͂��܂�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B");


insert into wp_wpd_catalog values(
"32",
"rescuer",
"VARCHAR(30)",
"",
"text",
"input",
"ajax_autocomplete",
"",
"����J",
"",
"",
"",
"�a�肳�񎁖�",
"�a�肳��̎������h�̗��œ��͂��܂�<BR>�Q�l�l ���N���b�N����Ɠo�^�ς݂̃f�[�^���\�������̂ŁA�Q�l�ɂ��ĉ������B"
);


insert into wp_wpd_catalog values(
"33",
"foster",
"VARCHAR(30)",
"",
"text",
"input",
"",
"",
"����J",
"",
"",
"",
"���n�掁��",
"���n��̎������h�̗��œ��͂��܂��B"
);

