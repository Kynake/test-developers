CREATE TABLE documents(
   id SERIAL PRIMARY KEY      NOT NULL,
   name           TEXT UNIQUE NOT NULL,
   content        TEXT,
   timestamp      TIMESTAMP   NOT NULL
);

CREATE TABLE signatures(
  id SERIAL PRIMARY KEY      NOT NULL,
  name           TEXT UNIQUE NOT NULL,
  issuer         TEXT        NOT NULL,
  ordering       INT         NOT NULL,
  timestamp      TIMESTAMP WITH TIME ZONE NOT NULL,
  id_document    INT NOT NULL REFERENCES documents(ID)
);

INSERT INTO documents (name, content, timestamp) VALUES (
  'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', CURRENT_TIMESTAMP
);

INSERT INTO documents (name, content, timestamp) VALUES (
  'Teste2', 'I Mendeley Mendeley', CURRENT_TIMESTAMP
);


INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Fulano da Silva', 'Empresa Certificadora 1', '1', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Beltrano da Silva', 'Empresa Certificadora 2', '2', CURRENT_TIMESTAMP, '1'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
 'Ciclano da Silva', 'Empresa Certificadora 3', '1', CURRENT_TIMESTAMP, '2'
);

INSERT INTO signatures (name, issuer, ordering, timestamp, id_document) VALUES (
  'Deltrano da Silva', 'Empresa Certificadora 4', '2', CURRENT_TIMESTAMP, '2'
);
