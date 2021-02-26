CREATE SCHEMA IF NOT EXISTS mgg;

CREATE  TABLE mgg.meeting ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	title                varchar(225) DEFAULT 'Meeting'::character varying  ,
	description          text   ,
	"password"           varchar(100)   ,
	video                boolean DEFAULT true  ,
	audio                boolean DEFAULT true  ,
	sharedscreen         boolean DEFAULT true  ,
	locked               boolean DEFAULT false  ,
	locked_at            timestamp(0)   ,
	start_at             timestamp(22) DEFAULT CURRENT_TIMESTAMP  ,
	end_at               timestamp(22)   ,
	created_at           timestamp(22) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(22)   ,
	deleted_at           timestamp(22)   ,
	CONSTRAINT pk_meeting_id PRIMARY KEY ( id )
 );

COMMENT ON COLUMN mgg.meeting.video IS 'video allowed';

COMMENT ON COLUMN mgg.meeting.audio IS 'Audio allowed';

COMMENT ON COLUMN mgg.meeting.locked IS 'Users can enter or not';

CREATE  TABLE mgg.discussion ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	meeting_id           uuid   ,
	title                varchar(225)   ,
	avatar               text   ,
	color                char(7)   ,
	favorite             boolean DEFAULT false  ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_chat_id PRIMARY KEY ( id )
 );

COMMENT ON COLUMN mgg.discussion.meeting_id IS 'if meeting_id null, it''s not a meeting discussion, it''s a private discussion group';

COMMENT ON COLUMN mgg.discussion.color IS 'A color can contain 6 digits and #. #000000';

CREATE  TABLE mgg.contact ( 
	user_id              uuid  NOT NULL ,
	target_id            uuid  NOT NULL ,
	status               smallint DEFAULT 0 NOT NULL ,
	starred              jsonb DEFAULT '[]'::jsonb  ,
	blocked              boolean DEFAULT false NOT NULL ,
	blocked_at           timestamp(0)   ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	accepted_at          timestamp(0)   ,
	CONSTRAINT pk_contact_user_id PRIMARY KEY ( user_id, target_id ),
	CONSTRAINT unq_contact_target_id UNIQUE ( target_id ) 
 );

CREATE  TABLE mgg.discussionusers ( 
	user_id              uuid  NOT NULL ,
	invite_id            uuid   ,
	discussion_id        uuid  NOT NULL ,
	permissions          jsonb DEFAULT '[]'::jsonb  ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_discussionusers PRIMARY KEY ( user_id, discussion_id )
 );

CREATE  TABLE mgg."group" ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	title                varchar(225)  NOT NULL ,
	"count"              smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	CONSTRAINT pk_groupcontact_id PRIMARY KEY ( id )
 );

CREATE  TABLE mgg.groupcontacts ( 
	group_id             uuid  NOT NULL ,
	contact_id           uuid  NOT NULL ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_groupcontacts PRIMARY KEY ( contact_id, group_id )
 );

CREATE  TABLE mgg.invite ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	meeting_id           uuid   ,
	"type"               smallint DEFAULT 0 NOT NULL ,
	"limit"              integer DEFAULT 1 NOT NULL ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(0)   ,
	expirated_at         timestamp(0)  NOT NULL ,
	CONSTRAINT pk_meetinginvite_id PRIMARY KEY ( id )
 );

CREATE  TABLE mgg.meetingusers ( 
	meeting_id           uuid  NOT NULL ,
	user_id              uuid  NOT NULL ,
	invite_id            uuid   ,
	permissions          jsonb DEFAULT '[]'::jsonb  ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp(22) DEFAULT CURRENT_TIMESTAMP  ,
	updated_at           timestamp(22)   ,
	deleted_at           timestamp(22)   ,
	CONSTRAINT pk_meetingusers PRIMARY KEY ( user_id, meeting_id )
 );

CREATE  TABLE mgg.message ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	discussion_id        uuid  NOT NULL ,
	content              text   ,
	"file"               text   ,
	meta_file            jsonb   ,
	starred              smallint DEFAULT 0  ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(0)   ,
	deleted_at           timestamp(0)   ,
	CONSTRAINT pk_message_id PRIMARY KEY ( id ),
	CONSTRAINT unq_message_discussion_id UNIQUE ( discussion_id ) 
 );

CREATE  TABLE mgg.notification ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	user_id              uuid  NOT NULL ,
	sender_id            uuid   ,
	meeting_id           uuid   ,
	discussion_id        uuid   ,
	title                varchar(225)   ,
	content              varchar(500)   ,
	status               smallint DEFAULT 0 NOT NULL ,
	created_at           timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	CONSTRAINT pk_notification_id PRIMARY KEY ( id )
 );

CREATE  TABLE mgg."user" ( 
	id                   uuid DEFAULT uuid_generate_v4() NOT NULL ,
	invite_id            uuid   ,
	firstname            varchar(225)   ,
	lastname             varchar(225)   ,
	username             varchar(100)   ,
	email                varchar(225)  NOT NULL ,
	"password"           text  NOT NULL ,
	avatar               text   ,
	phone                varchar(25)   ,
	fax                  varchar(25)   ,
	address              varchar(225)   ,
	city                 varchar(100)   ,
	country              varchar(100)   ,
	status               smallint DEFAULT 0  ,
	created_at           timestamp(22) DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	updated_at           timestamp(22) DEFAULT CURRENT_TIMESTAMP  ,
	permissions          jsonb DEFAULT '[]'::jsonb  ,
	CONSTRAINT pk_user_id PRIMARY KEY ( id )
 );

ALTER TABLE mgg.contact ADD CONSTRAINT fk_contact_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.contact ADD CONSTRAINT fk_contact_user_0 FOREIGN KEY ( target_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.discussion ADD CONSTRAINT fk_discussion_meeting FOREIGN KEY ( meeting_id ) REFERENCES mgg.meeting( id );

ALTER TABLE mgg.discussionusers ADD CONSTRAINT fk_discussionusers_discussion FOREIGN KEY ( discussion_id ) REFERENCES mgg.discussion( id );

ALTER TABLE mgg.discussionusers ADD CONSTRAINT fk_discussionusers_invite FOREIGN KEY ( invite_id ) REFERENCES mgg.invite( id );

ALTER TABLE mgg.discussionusers ADD CONSTRAINT fk_discussionusers_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg."group" ADD CONSTRAINT fk_group_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.groupcontacts ADD CONSTRAINT fk_groupcontacts_contact FOREIGN KEY ( contact_id ) REFERENCES mgg.contact( target_id );

ALTER TABLE mgg.groupcontacts ADD CONSTRAINT fk_groupcontacts_group FOREIGN KEY ( group_id ) REFERENCES mgg."group"( id );

ALTER TABLE mgg.invite ADD CONSTRAINT fk_meetinginvite_meeting FOREIGN KEY ( meeting_id ) REFERENCES mgg.meeting( id );

ALTER TABLE mgg.invite ADD CONSTRAINT fk_meetinginvite_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.meetingusers ADD CONSTRAINT fk_meetingusers_meetinginvite FOREIGN KEY ( invite_id ) REFERENCES mgg.invite( id );

ALTER TABLE mgg.meetingusers ADD CONSTRAINT fk_meetingusers_meeting FOREIGN KEY ( meeting_id ) REFERENCES mgg.meeting( id );

ALTER TABLE mgg.meetingusers ADD CONSTRAINT fk_meetingusers_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.message ADD CONSTRAINT fk_message_discussion FOREIGN KEY ( discussion_id ) REFERENCES mgg.discussion( id );

ALTER TABLE mgg.message ADD CONSTRAINT fk_message_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.notification ADD CONSTRAINT fk_notification_discussion FOREIGN KEY ( discussion_id ) REFERENCES mgg.discussion( id );

ALTER TABLE mgg.notification ADD CONSTRAINT fk_notification_meeting FOREIGN KEY ( meeting_id ) REFERENCES mgg.meeting( id );

ALTER TABLE mgg.notification ADD CONSTRAINT fk_notification_user FOREIGN KEY ( user_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg.notification ADD CONSTRAINT fk_notification_user_0 FOREIGN KEY ( sender_id ) REFERENCES mgg."user"( id );

ALTER TABLE mgg."user" ADD CONSTRAINT fk_user_meetinginvite FOREIGN KEY ( invite_id ) REFERENCES mgg.invite( id );

