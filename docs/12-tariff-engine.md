# 12 — Tariff engine

| Delivery | Phase V · step **81** |
| --- | --- |
| Prerequisites | [12-use-cases.md](12-use-cases.md) · [06-rules.md](06-rules.md) |
| Implementation | `app/Domain/Tariff/TariffResolver.php` · step 101 |

Resolves **published, effective** tariff versions and line items for public and admin read paths ([FR-TA-04](04-requirements.md), [FR-TA-05](04-requirements.md), [BR-TARIFF-001](06-rules.md)).

---

## 1. Responsibilities

| Operation | Description |
| --- | --- |
| `resolveEffectiveVersion(asOfDate?)` | Single published version effective on date |
| `resolveItems(version, filters?)` | Matrix lines with centre/category/service scope |
| `findPrice(centre, category, service?)` | Single amount for finder/handoff |
| `isPubliclyVisible(version)` | false for draft/reviewed |

---

## 2. Effective version selection

**Input:** `asOfDate` — calendar date in **Africa/Douala** (default today).

**Query logic:**

```sql
SELECT * FROM tariff_versions
WHERE status = 'published'
  AND effective_from <= :asOfDate
  AND (effective_until IS NULL OR effective_until >= :asOfDate)
ORDER BY effective_from DESC
LIMIT 1
```

**Invariant:** at most one row ([FR-TA-04](04-requirements.md)). `PublishTariffVersion` enforces in transaction:

1. `SELECT … FOR UPDATE` published rows overlapping effective range
2. Archive overlapping published → `archived`
3. Publish new version

If zero rows → `EffectiveTariffResult::empty()` → UI empty state [FR-TA-09](04-requirements.md) — **never** invent prices [BR-COMP-003](06-rules.md).

---

## 3. Lifecycle visibility

| Status | Public | Admin |
| --- | :---: | :---: |
| `draft` | hidden | editable |
| `reviewed` | hidden | ready to publish |
| `published` | visible if effective | read-only items |
| `archived` | hidden | read-only audit |

Draft/reviewed invisible publicly ([07-acceptance.md](07-acceptance.md) § Tariffs).

---

## 4. Item resolution & scoping

Each `tariff_items` row:

| Field | Scope rule |
| --- | --- |
| `vehicle_category_id` | required |
| `service_id` | optional — if set, line applies only to that service |
| `tariff_item_centre` pivot | line applies only at listed centres [BR-TARIFF-004](06-rules.md) |

**Filter** (`ResolveEffectiveTariff` input):

| Filter | Behaviour |
| --- | --- |
| `centreId` | Keep items linked to centre |
| `vehicleCategoryId` | Keep matching category |
| `serviceId` | Keep items where `service_id` IS NULL (generic) OR matches |

**Amount:** integer XAF → `MoneyXaf` VO → display `25 000 FCFA` grouped [BR-TARIFF-002](06-rules.md).

**Notes:** `validity_notes` JSON `{fr,en}` rendered on matrix footnotes.

---

## 5. Consistency rules

| Rule | Enforcement |
| --- | --- |
| Finder ≡ Tarifs page price | Same `TariffResolver` instance / cache key |
| Homepage finder | Calls `findPrice` — no duplicate SQL |
| Appointment handoff | Prefill category + centre from finder context [FR-TA-06](04-requirements.md) |
| Publish transactional | Archive prior + publish new one commit [BR-TARIFF-003](06-rules.md) |
| No silent edit of published | Items immutable once published; new version required |

---

## 6. Workflow states (admin)

Aligns with [design/admin/70-tariff-publish.html](../design/admin/70-tariff-publish.html):

```text
draft ──MarkTariffVersionReviewed──► reviewed ──PublishTariffVersion──► published ──(next publish)──► archived
```

| Step | Use case | Preconditions |
| --- | --- | --- |
| Create draft | `CreateTariffVersionDraft` | unique label |
| Edit items | `UpdateTariffVersionItems` | status draft or reviewed |
| Review | `MarkTariffVersionReviewed` | ≥1 item, valid dates |
| Publish | `PublishTariffVersion` | status reviewed, ops/super role |

Centre Manager **cannot** publish ([10-admin.md](10-admin.md)).

---

## 7. Class design

```text
app/Domain/Tariff/
  TariffResolver.php
  EffectiveTariffResult.php
  TariffLineDto.php
```

```php
final class TariffResolver
{
    public function resolveEffective(CarbonImmutable $asOfDate): EffectiveTariffResult;

    public function findPrice(
        CarbonImmutable $asOfDate,
        int $centreId,
        int $vehicleCategoryId,
        ?int $serviceId = null,
    ): ?MoneyXaf;
}
```

---

## 8. Caching

Cache key `tariff:effective:{date}` and `tariff:matrix:{versionId}` ([12-cache-policy.md](12-cache-policy.md)).

Invalidate on `TariffVersionPublished` event.

---

## 9. Test matrix (Pest · step 101)

| Test | Rule |
| --- | --- |
| Draft invisible on public resolver | FR-TA-04 |
| Publish B archives A | BR-TARIFF-003 |
| Finder and resolver same price | FR-TA-05 |
| Empty catalogue → null result | FR-TA-09 |
| Item not at centre excluded | BR-TARIFF-004 |
| FCFA formatting | BR-TARIFF-002 |

---

## 10. Acceptance (step 81)

- [x] Effective version query defined
- [x] Single published invariant documented
- [x] Item scoping by centre and service
- [x] Lifecycle visibility matrix
- [x] Workflow aligned with admin UX
- [x] Cache keys referenced

**Next:** Step **82** — Domain events.
